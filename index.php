<?php


require 'includes/config.php';
require 'includes/setting.php';


if(isset($_GET[$setting['site']['profilertrigger']]) || isset($_POST[$setting['site']['profilertrigger']]) || isset($_COOKIE[$setting['site']['profilertrigger']]))
{
	include (SITE_PATH . 'libs/pqp/classes/PhpQuickProfiler.php');
	$LITPI_PROFILER = new PhpQuickProfiler(PhpQuickProfiler::getMicroTime(), SITE_PATH . 'libs/pqp/');
	define('LITPI_PROFILER_ENABLE', 1);
}

require 'includes/permission.php';
require 'includes/startup.php';
require 'controllers/core/class.base.php';


if($conf['usingGZIP'])
{
	ob_start();
	ob_implicit_flush(false);
}

# Load router
$router = new Router($registry);
$registry->router = $router;
$router->setPath (SITE_PATH . 'controllers');
$router->delegate();



if(defined('LITPI_PROFILER_ENABLE'))
{
	$LITPI_PROFILER->display();	
}

if($conf['usingGZIP'])
{
	//Output the buffered data
	Helper::print_gzipped_page();
}

