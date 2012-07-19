<?php

Class Controller_Site_Install Extends Controller_Site_Base 
{
	function indexAction() 
	{
		if(!$this->checkinstallrequirement())
		{
			die('Install Error. User Account Existed.');
		}
		else
		{	
			$success = $error = $formData = array();
			
			if(isset($_POST['fsubmit']))
			{
				$formData = array_merge($formData, $_POST);
				
				if($this->installValidator($formData, $error))
				{
					//begin create new account
					$myUser = new Core_User();
					$myUser->fullname = $formData['ffullname'];
					$myUser->email = $formData['femail'];
					$myUser->password = viephpHashing::hash($formData['fpassword']);
					$myUser->groupid = GROUPID_ADMIN;
					
					if($myUser->addData())
					{
						$success[] = 'Administrator Account had been created.';
						$adminRedirectUrl = base64_encode($this->registry->conf['rooturl'] . 'admin');
						$this->registry->smarty->assign(array(
							'adminRedirectUrl' => $adminRedirectUrl,
						));
					}
					else
					{
						$error[] = 'Error while creating Administrator Account. Please try again.';
					}
					
				}
				
				$this->registry->smarty->assign(array(
					'error' => $error,
					'success' => $success,
					'formData' => $formData
				));
			}
			
			$this->registry->smarty->display($this->registry->smartyControllerContainer.'index.tpl'); 
		}
	} 
	
	
	
	private function installValidator($formData, &$error)
	{
		$pass = true;
		
		if(strlen($formData['ffullname']) == 0)
		{
			$pass = false;
			$error[] = 'Administrator Fullname is required.';
		}
		
		if(!Helper::ValidatedEmail($formData['femail']))
		{
			$pass = false;
			$error[] = 'Administrator Email is not valid.';
		}
		
		if(strlen($formData['fpassword']) == 0)
		{
			$pass = false;
			$error[] = 'Administrator Password is required.';
		}
		
		if(strcmp($formData['fpassword'], $formData['fpassword2']) != 0)
		{
			$pass = false;
			$error[] = 'Password and confirm password is not match.';
		}
		
		return $pass;
	}
	
	
	/**
	 * Check if there is no user in system before run install
	 */
	private function checkinstallrequirement()
	{
		$userCount = Core_User::getUsers(array(), '', '', '', true);
		

		
		if($userCount > 0)
			return false;
		else
			return true;
	}
	
	
}

?>