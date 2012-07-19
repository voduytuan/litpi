<?php

Class Router 
{
	private $registry;
	private $path;
	private $args = array();

	function __construct($registry) 
	{
		$this->registry = $registry;
	}

	function setPath($path) 
	{
		$firstChar = substr($path, 0, 1);
		$path = trim($path, '/\\');

		if($firstChar == '/')
		{
			$path = '/' . $path;
		}
		
		$path .= DIRECTORY_SEPARATOR;

		clearstatcache();
		if (is_dir($path) == false) 
		{
			throw new Exception ('Invalid controller path: `' . $path . '`');
		}

		$this->path = $path;
	}

	function getArg($key = '') 
	{
		if($key != '')
		{
			if (!isset($this->args[$key])) 
			{ 
				return null; 
			}
			
			return $this->args[$key];
		}
		else 
		{
			$output = '';
			//return full args string
			foreach ($this->args as $k => $v)
			{
				$output .= $k . '/' . $v . '/';
			}
			return $output;
		}
		
	}

	function delegate() 
	{
		// Analyze route
		$this->getController($file, $controllerGroup, $controller, $action, $args);

		//assign args 
		$this->extractArgs($args);
		
		// File available?
		if (is_readable($file) == false) 
		{
			$this->notFound('no-file');
		}
		// Include the file
		include ($file);
		

		// Initiate the class
		$class = 'Controller_' . $controllerGroup . '_' . $controller;
		$controller = new $class($this->registry);

		//refine action string : append Action
		$action .= 'Action';
		
		// Run action
		$controller->$action();
		
		
	}

	private function extractArgs($args) 
	{
		if (count($args) == 0) { return false; }
		$this->args = $args;
	}
	
	private function getController(&$file, &$controllerGroup, &$controller, &$action, &$args) 
	{
		$route = $GLOBALS['route'];

		
		// Get separate parts
		$route = trim($route, '/\\');
		$parts = explode('/', $route);
		
		array_walk($parts, array('Router', 'filterRouterInput'));
		
		$controllerGroup = array_shift($parts);
		$controller = array_shift($parts);
		$action = array_shift($parts);
		
		$file = $this->path . $controllerGroup . DIRECTORY_SEPARATOR . 'class.' . $controller . '.php';
		
		if(!file_exists($file) || !is_readable($file))
		{
			$this->notFound();
		} 
		
		if(count($parts) > 0)
		{
			$args = $this->parseArgsString($parts);
		}	
	}


	private function notFound($err = '') 
	{
		header('location: ' . $this->registry->conf['rooturl'] . 'site/notfound?r=' . base64_encode(Helper::curPageURL()));
		exit();
	}
	
	//param format: name1/value1/name2/value2
	private function parseArgsString($argArr)
	{
		$outputArr = array();
		
		for ($i = 0; $i < count($argArr); $i += 2)
		{
			if(isset($argArr[($i + 1)]))
			{
				$outputArr[$argArr[$i]] = strlen($argArr[($i + 1)]) == 0 ? '' : $argArr[($i + 1)];
			}
			else 
			{
				$outputArr[$argArr[$i]] = '';
			}
		}
		
		
		return $outputArr;
	}
	
	public function filterRouterInput($input)
	{
		$output = $input;
		
		$output = htmlspecialchars($output);
		return $output;
	}
	
	public static function initRoute($defaultControllerGroup = 'site')
	{
		global $conf;
		$route = '';
		
		
		//get the filename of the request URI online - after '?' character
		if(($pos = strpos($_SERVER['REQUEST_URI'], '?')) !== false)
			$cleanURI = substr($_SERVER['REQUEST_URI'], 0, $pos);
		else
			$cleanURI = $_SERVER['REQUEST_URI'];
			
		//detect default Controller Group for mobile browser
			
		
		if (empty($_GET['route']) || $cleanURI == '/' || $cleanURI == '/index.php' )
		{
			
			$route = $defaultControllerGroup;
		}
		else
			$route = $_GET['route'];
			
		return $route;
	}
	
	

}

