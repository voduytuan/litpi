<?php

Class Controller_Admin_SessionManager Extends Controller_Admin_Base 
{
	public $recordPerPage = 40;
	
	/**
	 * Xem cac ket noi thong qua session
	 *
	 */
	public function indexAction()
	{
		$error 	= array();
		$success 	= array();
		$formData = array();
		$contents 	= '';
		$page 		= (int)($this->registry->router->getArg('page'))>0?(int)($this->registry->router->getArg('page')):1;  
		
		//xoa theo batch
		if(isset($_POST['btnDel']))
		{
			//tien hanh xoa
			
			if(!isset($_POST['delid']) || count($_POST['delid']) == 0)
			{
				$error[] = 'No item selected';
			}
			else 
			{
				$delArr = $_POST['delid'];
				
				$successCount = 0;
				$errorCount = 0;
				
				foreach ($delArr as $id)
				{
					$mySession = new Core_Session();
					$mySession->id = $id;
					if($mySession->delete())
					{
						$successCount++;
					}
					else
					{
						$errorCount++;
					}
				}
				
				
				if($successCount > 0)
				{
					$success[] = 'Deleted ' . $successCount . ' session(s).';
				}
				
				if($errorCount > 0)
				{
					$error[] = 'Error while deleting ' . $errorCount . ' session(s).';
				}
				
			}
			
		}
		
		$sortby 	= $this->registry->router->getArg('sortby');
		if($sortby == '') $sortby = 'dateexpired';
		$formData['sortby'] = $sortby;
		
		$sorttype 	= $this->registry->router->getArg('sorttype');
		if(strtoupper($sorttype) != 'ASC') $sorttype = 'DESC';
		$formData['sorttype'] = $sorttype;
		
		//Filter Select query
		$ipFilter = $this->registry->router->getArg('fip');
		$sessionFilter = $this->registry->router->getArg('fsession');
		$ipFilter = isset($_POST['fip'])?$_POST['fip']:$ipFilter;
		$sessionFilter = isset($_POST['fsession'])?$_POST['fsession']:$sessionFilter;
		
		$paginateUrl = $this->registry->conf['rooturl_admin'].'sessionmanager/index/'; 
		
		
		if(strlen($ipFilter) > 0)
		{
			$paginateUrl .= 'fip/'.$ipFilter . '/';
			$formData['fip'] = $ipFilter;
			$formData['search'] = 'ip';
		}
		
		if(strlen($sessionFilter) > 0)
		{
			$paginateUrl .= 'fsession/'.$sessionFilter . '/';
			$formData['fsession'] = $sessionFilter;
			$formData['search'] = 'session';
		}
		
		//filter for sortby & sorttype
		$filterUrl = $paginateUrl;
		
		//append sort to paginate url
		$paginateUrl .= 'sortby/' . $sortby . '/sorttype/' . $sorttype . '/';
		
		//tim tong so record
		$total = Core_Session::getSessions($formData, $sortby, $sorttype, 0, true, true, true);
		$totalPage = ceil($total/$this->recordPerPage);
		$curPage = $page;
		
		$entries = Core_Session::getSessions($formData, $sortby, $sorttype, (($page - 1)*$this->recordPerPage).','.$this->recordPerPage, false, true, true);
		
		
		//build redirect string
		$redirectUrl = $paginateUrl;
		if($curPage > 1)
			$redirectUrl .= 'page/' . $curPage;
			
		
		$redirectUrl = base64_encode($redirectUrl);
		
		   
		$memberOnline = Core_Session::countmemberonline();
		
		
		$this->registry->smarty->assign(array('entries'				=> $entries,
												'total'				=> $total,
												'memberOnline'		=> $memberOnline,
												'formData'					=> $formData,
												'filterUrl'		=> $filterUrl,
												'paginateurl' 			=> $paginateUrl,
												'redirectUrl' 			=> $redirectUrl,              
												'totalPage' 			=> $totalPage,
												'curPage'				=> $curPage,
												'success' => $success,
												'error'	=> $error,
												));
												
		$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer.'index.tpl'); 
		$this->registry->smarty->assign(array('contents' => $contents, 
												'pageTitle'	=> 'Session Manager')
												);
		$this->registry->smarty->display($this->registry->smartyControllerGroupContainer.'index.tpl');     
	}
	
	
	/**
	 * Xem cac ket noi thong qua session
	 *
	 */
	public function indexfullAction()
	{
		$error 	= array();
		$success 	= array();
		$formData = array();
		$contents 	= '';
		$page 		= (int)($this->registry->router->getArg('page'))>0?(int)($this->registry->router->getArg('page')):1;  
		
		//xoa theo batch
		if(isset($_POST['btnDel']))
		{
			//tien hanh xoa
			
			if(!isset($_POST['delid']) || count($_POST['delid']) == 0)
			{
				$error[] = 'No item selected';
			}
			else 
			{
				$delArr = $_POST['delid'];
				
				$successCount = 0;
				$errorCount = 0;
				
				foreach ($delArr as $id)
				{
					$mySession = new Core_Session();
					$mySession->id = $id;
					if($mySession->delete())
					{
						$successCount++;
					}
					else
					{
						$errorCount++;
					}
				}
				
				if($successCount > 0)
				{
					$success[] = 'Deleted ' . $successCount . ' session(s).';
				}
				
				if($errorCount > 0)
				{
					$error[] = 'Error while deleting ' . $errorCount . ' session(s).';
				}	
			}	
		}
		
		
		$sortby 	= $this->registry->router->getArg('sortby');
		if($sortby == '') $sortby = 'dateexpired';
		$formData['sortby'] = $sortby;
		
		$sorttype 	= $this->registry->router->getArg('sorttype');
		if(strtoupper($sorttype) != 'ASC') $sorttype = 'DESC';
		$formData['sorttype'] = $sorttype;
		
		//Filter Select query
		$ipFilter = $this->registry->router->getArg('fip');
		$sessionFilter = $this->registry->router->getArg('fsession');
		$ipFilter = isset($_POST['fip'])?$_POST['fip']:$ipFilter;
		$sessionFilter = isset($_POST['fsession'])?$_POST['fsession']:$sessionFilter;
		
		$paginateUrl = $this->registry->conf['rooturl_admin'].'sessionmanager/indexfull/';
		
		if(strlen($ipFilter) > 0)
		{
			$paginateUrl .= 'fip/'.$ipFilter . '/';
			$formData['fip'] = $ipFilter;
			$formData['search'] = 'ip';
		}
		
		if(strlen($sessionFilter) > 0)
		{
			$paginateUrl .= 'fsession/'.$sessionFilter . '/';
			$formData['fsession'] = $sessionFilter;
			$formData['search'] = 'session';
		}
		
		//filter for sortby & sorttype
		$filterUrl = $paginateUrl;
		
		//append sort to paginate url
		$paginateUrl .= 'sortby/' . $sortby . '/sorttype/' . $sorttype . '/';
		
		
		
		//tim tong so record
		$total = Core_Session::getSessions($formData, $sortby, $sorttype, 0, true, true);
		$totalPage = ceil($total/$this->recordPerPage);
		$curPage = $page;
		
		$entries = Core_Session::getSessions($formData, $sortby, $sorttype, (($page - 1)*$this->recordPerPage).','.$this->recordPerPage, false, true);
		
		
		//build redirect string
		$redirectUrl = $paginateUrl;
		if($curPage > 1)
			$redirectUrl .= 'page/' . $curPage;
			
		
		$redirectUrl = base64_encode($redirectUrl);
		
		
		$memberOnline = Core_Session::countmemberonline();
		
		
		$this->registry->smarty->assign(array('entries'				=> $entries,
												'total'				=> $total,
												'memberOnline'		=> $memberOnline,
												'formData'					=> $formData,
												'filterUrl'		=> $filterUrl,
												'paginateurl' 			=> $paginateUrl,
												'redirectUrl' 			=> $redirectUrl,              
												'totalPage' 			=> $totalPage,
												'curPage'				=> $curPage,
												'success' => $success,
												'error'	=> $error,
												));
												
		$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer.'index.tpl'); 
		$this->registry->smarty->assign(array('contents' => $contents, 
												'pageTitle'	=> 'Session Manager')
												);
		$this->registry->smarty->display($this->registry->smartyControllerGroupContainer.'index.tpl');     
	}
	/**
	 * Xem detail 1 session
	 *
	 */
	public function detailAction()
	{
		$error = array();
		$success = array();
		
		$sessionid = $this->registry->router->getArg('sid');
		
		$mySession = new Core_Session($sessionid);
		if($mySession->id != '')
		{
			$mySession->browser = new browser($mySession->agent);
		}
		else
		{
			$error[] = 'Not Found.';
		}
		
		
		$this->registry->smarty->assign(array('mySession' => $mySession, 'error' => $error));
		$this->registry->smarty->display($this->registry->smartyControllerContainer.'detail.tpl'); 
		 
	}
	
	/**
	 * Kill 1 connection
	 *
	 */
	public function killAction()
	{
		$redirectMsg = '';
		
		$sessionid = $this->registry->router->getArg('sid');
		$ipFilter = $this->registry->router->getArg('fip');
		$sessionFilter = $this->registry->router->getArg('fsession');
		
		$mySession = new Core_Session($sessionid);
		if($mySession->id != '')
		{
			if($mySession->delete())
			{
				$redirectMsg = 'Delete OK';
				header("location: " . $_SERVER['HTTP_REFERER']);
				exit();
			}
			else 
			{
				$redirectMsg = 'Error delete form database'; 
			}
		}
		else
		{
			$redirectMsg = 'Not Found.';
		}
		
		
		$redirectUrl = 'sessionmanager/index/';
		if(strlen($ipFilter) > 0)
			$redirectUrl .= 'fip/' . $ipFilter . '/';
		if(strlen($sessionFilter) > 0)
			$redirectUrl .= 'fsession/' . $sessionFilter . '/';
		
		$this->registry->smarty->assign(array('redirect' =>  $redirectUrl ,
												'redirectMsg' => $redirectMsg));
		$this->registry->smarty->display('redirect.tpl');
	}
}

?>