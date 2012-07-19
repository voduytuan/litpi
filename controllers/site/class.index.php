<?php

Class Controller_Site_Index Extends Controller_Site_Base 
{
	function indexAction() 
	{
		$userCount = Core_User::getUsers(array(), '', '', '', true);
		
		//if there is no user, redirect to installation
		if($userCount == 0)
		{
			header('location: ' . $this->registry->conf['rooturl'] . 'site/install');
		}
		else
		{
			$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer.'index.tpl'); 
		
			$this->registry->smarty->assign(array('contents' => $contents));
		
			$this->registry->smarty->display($this->registry->smartyControllerGroupContainer.'index.tpl');
		}
		
	} 
	
	
	
	
}

?>