<?php

Class Controller_Site_NotPermission Extends Controller_Site_Base 
{
	
	function indexAction() 
	{
		$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer . 'index.tpl');
		$this->registry->smarty->assign('contents', $contents);
		$this->registry->smarty->display($this->registry->smartyControllerGroupContainer.'index.tpl');     	
	} 
}

?>