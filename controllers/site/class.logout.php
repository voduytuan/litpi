<?php

Class Controller_Site_Logout Extends Controller_Site_Base 
{
	
	public function indexAction()
	{
		session_regenerate_id(true);
		session_destroy();
		
		//clear remember me data
		setcookie('myHashing', "", time()-3600, '/');   
		
		header('location: ' . $this->registry->conf['rooturl'] . 'site/login');
	}
	
		
	
	
	
}

?>
