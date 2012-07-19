<?php

spl_autoload_register('autoload1');


if (get_magic_quotes_gpc()) 
{
    function stripslashes_gpc(&$value)
    {
        $value = stripslashes($value);
    }
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
}



//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//INIT REGISTRY VARIABLE - MAIN STORAGE OF APPLICATION
$registry = new Registry();

//=========================================================
//---IMPORTANT-------------------
// set base dir to correct the relative link
$route = parseRouterFromHtaccess(Router::initRoute('site'));

$parts = explode('/', $route);



if($parts[0])
{
	$GLOBALS['controller_group'] = $parts[0];
	$conf['servername'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $parts[0] . '/';    
}



if(!empty($parts[1]))
{
	$GLOBALS['controller'] = $parts[1];	
	if(!empty($parts[2]))
	{
		$GLOBALS['action'] = $parts[2];
	}
	else
	{
		$GLOBALS['action'] = 'index';
		$route = $GLOBALS['controller_group'] . '/' . $GLOBALS['controller'] . '/' . 'index';
	}
}
else
{
	$GLOBALS['controller'] = 'index';
	$GLOBALS['action'] = 'index';
	$route = $GLOBALS['controller_group'] . '/' . 'index' . '/' . 'index';
}



$GLOBALS['route'] = $route;
for ($i = 0; $i < count($parts) - 1; $i++)
{
	Registry::$base_dir .= '../';
}


//=========================================================
//connect to database using PDO
try 
{
	$db = new MyPDO('mysql:host=' . $conf['db']['host'] . ';dbname=' . $conf['db']['name'] . '', '' . $conf['db']['user'] . '', '' . $conf['db']['pass'] . '');
	$db->query('SET NAMES utf8');

}
catch(PDOException $e)
{
	die('Database connection failed.');
}
//unset the database config information for not leak security
unset($conf['db']);


////////////////////////////////////////////
// customize for mobile app
// because mobile app will use token (same meaning as session cookie)
// we will use this token data and set it as this session id
// it must be before session_start(), and sure, before new dbsession() init
if(!empty($_REQUEST['g_token']))
{
	$tokenPart = explode('-', $_REQUEST['g_token']);
	$tokenId = $tokenPart[0];
	$tokenSessionId = $tokenPart[1];
	$tokenUserId = $tokenPart[2];
	
	session_id($tokenSessionId);
	
}


$session = new dbsession($db);




//===================================================
//get language
if(isset($_GET['language']))
{
	$_SESSION['language'] = $_GET['language'];
	setcookie('language', $_GET['language'], time() + 24 * 3600, '/');
}

if(isset($_POST['language']))
{
	$_SESSION['language'] = $_POST['language'];
	setcookie('language', $_POST['language'], time() + 24 * 3600, '/');
}



if(isset($_SESSION['language']))
	$langCode = $_SESSION['language'];
elseif(isset($_COOKIE['language']))
	$langCode = $_COOKIE['language'];
else
	$langCode = $conf['defaultLang']; 

$langCode = substr($langCode, 0, 2);

			
//declare language variable
$lang = array();
$lang['global'] = Helper::GetLangContent('language' . DIRECTORY_SEPARATOR  . $langCode . DIRECTORY_SEPARATOR, 'global');
$lang['default'] = Helper::GetLangContent('language' . DIRECTORY_SEPARATOR . $langCode . DIRECTORY_SEPARATOR . $GLOBALS['controller_group'] . DIRECTORY_SEPARATOR, 'default');
$lang['controller'] = Helper::GetLangContent('language' . DIRECTORY_SEPARATOR . $langCode . DIRECTORY_SEPARATOR . $GLOBALS['controller_group'] . DIRECTORY_SEPARATOR, $GLOBALS['controller']);


//register an object to hold all global objects
$registry->conf = $conf;
$registry->db = $db;
$registry->setting = $setting;
$registry->lang = $lang;
$registry->langCode = $langCode;
$registry->controller = $GLOBALS['controller'];  
$registry->controllerGroup = $GLOBALS['controller_group']; 
$registry->action = $GLOBALS['action']; 
//create flag to check user browser support gzip or not
$registry->supportgzip = (false !== strpos(strtolower($_SERVER['HTTP_ACCEPT_ENCODING']), 'gzip'));

//=============================
// CURRENT VISITOR
// Authentication & authorization
$me = new Core_User();  
$me->updateFromSession();	//authentication
$me->checkPerm();
$registry->me = $me; 
//end authen & authorization

//Include Smarty class
include(SITE_PATH. 'libs' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'Smarty.class.php');
$smarty = new Smarty();

//set current template
$currentTemplate = 'default';
$registry->currentTemplate =  $registry->getResourceHost('static');

$smarty->template_dir = 'templates/' . $currentTemplate;
$smarty->compile_dir = 'templates/_core/templates_c/';
$smarty->config_dir = 'templates/_core/configs/';
$smarty->cache_dir = 'templates/_core/cache/';
$smarty->compile_id = $currentTemplate;	//seperate compiled template file 
$smarty->error_reporting = E_ALL ^ E_NOTICE;


$smarty->assign(array('base_dir' => Registry::$base_dir,
					  'registry' => $registry,
					  'langCode' => $langCode,
					  'lang' => $lang,
					  'setting'	=> $setting,
					  'controller' => $GLOBALS['controller'],
					  'controllerGroup' => $GLOBALS['controller_group'],
					  'action' => $GLOBALS['action'], 
					  'redirect' => base64_encode($GLOBALS['route']),
					  'currentTemplate'	=> $registry->getResourceHost(),
					  'imageDir' => $registry->getResourceHost() . 'images/',
					  'me' => $me,
					  'conf' => $conf));
$registry->smarty = $smarty;

				
Helper::fixBackButtonOnIE();

function autoload1($classname)
{
	$namepart = explode('_', $classname);
	$namepartCount = count($namepart);
	
	if($namepartCount > 1)
	{
		if($namepart[0] == 'Controller')
		{
			$filepath = '';
			for($i = 1; $i < $namepartCount - 1; $i++)
			{
				$filepath .= strtolower($namepart[$i]) . DIRECTORY_SEPARATOR;
			}
			$filename = SITE_PATH . 'controllers' . DIRECTORY_SEPARATOR . $filepath . 'class.' . strtolower($namepart[$namepartCount - 1]) . '.php';
		}
		else
		{
			$filepath = '';
			for($i = 0; $i < $namepartCount - 1; $i++)
			{
				$filepath .= strtolower($namepart[$i]) . DIRECTORY_SEPARATOR;
			}
			$filename = SITE_PATH . 'classes' . DIRECTORY_SEPARATOR . $filepath . 'class.' . strtolower($namepart[$namepartCount - 1]) . '.php';
		}
		
	}
	else
		$filename = SITE_PATH . 'classes' . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
		
	
	if (file_exists($filename) == false) 
	{
		return false;
	}
	

	include ($filename);
}




/////////////////////////////////////////////////
/////////////////////////////////////////////////
// Special URL rewrite rule for current website besind the default rewrite rule of website
// output $route must be have the format: controllergroup/controller/action/param1/value1/param2/value2...
function parseRouterFromHtaccess($route)
{
	///////////////////////////////////////////////
	//START customize URL
	
	
	//END customize URL
	/////////////////////////////////////////////////
	
	return $route;
}


