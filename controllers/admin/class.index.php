<?php

Class Controller_Admin_Index Extends Controller_Admin_Base 
{
	
	function indexAction() 
	{
		global $session;
		
		$server_php = $_SERVER['SERVER_SOFTWARE'];
		$pos = strripos($server_php, 'php');
		$formData['fserverip'] = $_SERVER['SERVER_ADDR'];
		$formData['fserver'] = trim(substr($server_php, 0, $pos-1));
		$formData['fphp'] = trim(substr($server_php, $pos));
		
		
		$this->registry->smarty->assign(array('formData' => $formData,
												));
		$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer.'index.tpl'); 
		
		
        $this->registry->smarty->assign(array(
                                                'pageTitle'    => $this->registry->lang['controller']['title'],
                                                'contents'             => $contents));
        $this->registry->smarty->display($this->registry->smartyControllerGroupContainer . 'index.tpl');
		
	} 
}

?>