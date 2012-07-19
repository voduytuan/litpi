<?php

Abstract Class Controller_Core_Base 
{
	protected $registry;

	function __construct($registry) 
	{
		
		
		
		//set smarty template container
		$registry->set('smartyControllerContainerRoot', '_controller/');
		$registry->set('smartyControllerGroupContainer', '_controller/' . $registry->controllerGroup . '/');
		$registry->set('smartyControllerContainer', '_controller/' . $registry->controllerGroup.'/'.$registry->controller . '/');
		$registry->set('smartyMailContainerRoot', '_mail/');
		
		$registry->smarty->assign(array('smartyControllerContainerRoot'	=> '_controller/', 
										'smartyControllerGroupContainer' => '_controller/' . $registry->controllerGroup . '/',
										  'smartyControllerContainer' => '_controller/' .  $registry->controllerGroup.'/' . $registry->controller . '/', 
										  'smartyMailContainerRoot' => '_mail/', 
										  ));
		$this->registry = $registry;
	}

	function __call($name, $args)
	{
		header('location: ' . $this->registry->conf['rooturl'] . 'notfound.php');
		exit();
	}
	
	
	
	
	abstract function indexAction();
	
	
	
	
}
?>