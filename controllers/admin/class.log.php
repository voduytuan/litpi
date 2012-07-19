<?php

Class Controller_Admin_Log Extends Controller_Admin_Base 
{
	private $recordPerPage = 30;
	
	function indexAction() 
	{
		$error 			= array();
		$success 		= array();
		$warning 		= array();
		$formData 		= array('fbulkid' => array());
		$page 			= (int)($this->registry->router->getArg('page'))>0?(int)($this->registry->router->getArg('page')):1;
		
		$uidFilter 		= (int)($this->registry->router->getArg('uid'));
		$groupFilter 	= (int)($this->registry->router->getArg('group'));
		$typeFilter 	= trim($this->registry->router->getArg('type'));
		$ipFilter 		= trim($this->registry->router->getArg('ip')); 
		
		//check sort column condition
		$sortby 	= $this->registry->router->getArg('sortby');
		if($sortby == '') $sortby = 'id';
		$formData['sortby'] = $sortby;
		$sorttype 	= $this->registry->router->getArg('sorttype');
		if(strtoupper($sorttype) != 'ASC') $sorttype = 'DESC';
		$formData['sorttype'] = $sorttype;	
		
		
		if(!empty($_POST['fsubmitbulk']))
		{
			if(!isset($_POST['fbulkid']))
			{
				$warning[] = $this->registry->lang['default']['bulkItemNoSelected'];
			}
			else
			{
				$formData['fbulkid'] = $_POST['fbulkid'];
				
				//check for delete 
				if($_POST['fbulkaction'] == 'delete')
				{
					$delArr = $_POST['fbulkid'];
					$deletedLogs = array();
					$cannotDeletedLogs = array();
					foreach($delArr as $id)
					{
						//check valid user and not admin user
						$myLog = new Core_ModeratorLog($id);
						
						if($myLog->id > 0)
						{
							//tien hanh xoa
							if($myLog->delete())
							{
								$deletedLogs[] = $myLog->id;
							}
							else
								$cannotDeletedLogs[] = $myLog->id;
						}
						else
							$cannotDeletedLogs[] = $myLog->id;
					}
					
					if(count($deletedLogs) > 0)
						$success[] = str_replace('###logid###', implode(', #', $deletedLogs), $this->registry->lang['controller']['succDelete']);
					
					if(count($cannotDeletedLogs) > 0)
						$error[] = str_replace('###logid###', implode(', #', $cannotDeletedLogs), $this->registry->lang['controller']['errDelete']);
				}
				else
				{
					//bulk action not select, show error
					$warning[] = $this->registry->lang['default']['bulkActionInvalidWarn'];
				}
			}
			
			
			//tien hanh xoa
			
		}
				
		$paginateUrl = $this->registry->conf['rooturl_admin'].'log/index/';  
		    
		if($uidFilter > 0)
		{
			$paginateUrl .= 'uid/'.$uidFilter . '/';
			$formData['fuid'] = $idFilter;
			$formData['search'] = 'uid';
		}
		
				
		if(strlen($typeFilter) > 0)
		{
			$paginateUrl .= 'type/'.$typeFilter . '/';
			$formData['ftype'] = $typeFilter;
			$formData['search'] = 'type';
		}
		
		if(strlen($ipFilter) > 0)
		{
			$paginateUrl .= 'ip/'.$ipFilter . '/';
			$formData['fip'] = $ipFilter;
			$formData['search'] = 'ip';
		}
		
		//tim tong so record
		$total = Core_ModeratorLog::getLogs($formData, $sortby, $sorttype, 0, true);
		$totalPage = ceil($total/$this->recordPerPage);
		$curPage = $page;
		
			
		//get latest records
		$logsTmp = Core_ModeratorLog::getLogs($formData, $sortby, $sorttype, (($page - 1)*$this->recordPerPage).','.$this->recordPerPage);
		$logs = array();
		foreach($logsTmp as $log)
		{
			$log->groupname = Core_User::groupname($log->groupid);
			$logs[] = $log;
		}
		
		//build redirect string
		$redirectUrl = $paginateUrl;
		if($curPage > 1)
			$redirectUrl .= 'page/' . $curPage;
			
		
		$redirectUrl = base64_encode($redirectUrl);
		
				
		$this->registry->smarty->assign(array(	'logs' 			=> $logs,
												'formData'		=> $formData,
												'success'		=> $success,
												'error'			=> $error,
												'warning'		=> $warning,
												'paginateurl' 	=> $paginateUrl, 
												'redirectUrl'	=> $redirectUrl,
												'total'			=> $total,
												'totalPage' 	=> $totalPage,
												'curPage'		=> $curPage
												));
		
		
		$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer.'index.tpl');
		
		$this->registry->smarty->assign(array(	'pageTitle'	=> $this->registry->lang['controller']['pageTitle_list'],
												'contents' 	=> $contents));
		$this->registry->smarty->display($this->registry->smartyControllerGroupContainer . 'index.tpl');
		
	} 
	
	
	function detailAction()
	{
		$id = (int)$this->registry->router->getArg('id');
		$myLog = new Core_ModeratorLog($id);
		
		$redirectUrl = $this->getRedirectUrl();
			
		//check valid log
		if($myLog->id > 0)
		{
			$myLog->groupname = Core_User::groupname($myLog->groupid);
			$contents 	= '';               
			$this->registry->smarty->assign(array(	'redirectUrl'=> $redirectUrl,
													'encodedRedirectUrl' => base64_encode($redirectUrl),
													'log'		=> $myLog,
													));
			$contents .= $this->registry->smarty->fetch($this->registry->smartyControllerContainer.'detail.tpl');
			$this->registry->smarty->assign(array(
													'pageTitle'	=> $this->registry->lang['controller']['pageTitle_detail'],
													'contents' 			=> $contents));
			$this->registry->smarty->display($this->registry->smartyControllerGroupContainer . 'index.tpl');		
		}
		else
		{
			$redirectMsg = $this->registry->lang['controller']['errNotFound'];
			$this->registry->smarty->assign(array('redirect' => $redirectUrl,
													'redirectMsg' => $redirectMsg,
													));
			$this->registry->smarty->display('redirect.tpl');
		}
		
		
	}	
	
	
	function deleteAction()
	{
		$id = (int)$this->registry->router->getArg('id');
		$myLog = new Core_ModeratorLog($id);
			
		//check valid log
		if($myLog->id > 0)
		{
			if(Helper::checkSecurityToken())
			{
				//tien hanh xoa
				if($myLog->delete())
				{
					$redirectMsg = str_replace('###logid###', $myLog->id, $this->registry->lang['controller']['succDelete']);
				}
				else
				{
					$redirectMsg = str_replace('###logid###', $myLog->id, $this->registry->lang['controller']['errDelete']);
				}	
			}
			else
				$redirectMsg = $this->registry->lang['default']['errFormTokenInvalid'];   
			
			
		}
		else
		{
			$redirectMsg = $this->registry->lang['controller']['errNotFound'];
		}
		
		$this->registry->smarty->assign(array('redirect' => $this->getRedirectUrl(),
												'redirectMsg' => $redirectMsg,
												));
		$this->registry->smarty->display('redirect.tpl');
			
	}
	
	
	####################################################################################################
	####################################################################################################
	####################################################################################################

	
}

?>
