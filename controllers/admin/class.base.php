<?php

Abstract Class Controller_Admin_Base Extends Controller_Core_Base 
{
	public function __construct($registry)
	{
		
		parent::__construct($registry);
		
		
	}	
	
		
	protected function getRedirectUrl()
	{
		$redirectUrl = $this->registry->router->getArg('redirect');
		if(strlen($redirectUrl) > 0)
			$redirectUrl = base64_decode($redirectUrl);	
		else
			$redirectUrl = $this->registry->conf['rooturl_admin'] . $this->registry->controller .'/index';

		return $redirectUrl;
	}
}
?>