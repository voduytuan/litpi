<!DOCTYPE html>
<html lang="en">
  <head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>{$lang.default.adminPanel} &raquo; {$pageTitle|default:$lang.default.menuDashboard}</title>
		
		<!-- Bootstrap Stylesheet -->
		<link rel="stylesheet" href="{$currentTemplate}/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
	  
		<!-- Bootstrap Responsive Stylesheet -->
		<link rel="stylesheet" href="{$currentTemplate}/bootstrap/css/bootstrap-responsive.min.css" type="text/css" media="screen" />
        
		<!-- Customized Admin Stylesheet -->
		<link rel="stylesheet" href="{$currentTemplate}/css/admin/mystyle.css" type="text/css" media="screen" />
        
		<!-- jQuery -->
		<script type="text/javascript" src="{$currentTemplate}/js/jquery.js"></script>
		
		<!-- Bootstrap Js -->
		<script type="text/javascript" src="{$currentTemplate}/bootstrap/js/bootstrap.min.js"></script>
		
		
		<!-- customized admin -->
		<script type="text/javascript" src="{$currentTemplate}/js/admin/admin.js"></script>
		
		
        <script type="text/javascript">
		var rooturl = "{$conf.rooturl}";
		var rooturl_admin = "{$conf.rooturl_admin}";
		var controllerGroup = "{$controllerGroup}";
		var currentTemplate = "{$currentTemplate}";
		var delConfirm = "Are You Sure?";
		var delPromptYes = "Type YES to continue";
		</script>
		
	</head>
    
    <body>
	
		<div class="navbar navbar-fixed-top">
		   <div class="navbar-inner">
	        <div class="container-fluid">
	          <a class="brand" href="{$conf.rooturl_admin}" title="Go to Dashboard">Litpi Control Panel</a>
	          <div class="btn-group pull-right">
	            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
	              <i class="icon-user"></i> {$me->fullname}
	              <span class="caret"></span>
	            </a>
	            <ul class="dropdown-menu">
	              <li><a href="{$conf.rooturl_admin}user/edit/id/{$me->id}"><i class="icon-pencil"></i> Edit Profile</a></li>
				  <li><a href="{$conf.rooturl_admin}user/changepassword/id/{$me->id}"><i class="icon-lock"></i> Change Password</a></li>
	              <li class="divider"></li>
	              <li><a href="{$conf.rooturl}site/logout"><i class="icon-off"></i> Sign Out</a></li>
	            </ul>
	          </div>
	          <div class="nav-collapse">
			    <ul class="nav">
	              <li><a href="{$conf.rooturl}">Website</a></li>
	            </ul>
	          </div>
	        </div>
	      </div>
	    </div>
		
		<div class="container-fluid">
		  <div class="row-fluid">
	        <div class="span2">
	          <div class="well sidebar-nav">
	            <ul class="nav nav-list" id="sidebar">
	              <li class="nav-header"><i class="icon-user"></i> User</li>
	              <li id="menu_user_list"><a href="{$conf.rooturl_admin}user">View all users</a></li>
				  <li id="menu_log"><a href="{$conf.rooturl_admin}log">Moderator Log</a></li>
				
	              <li class="nav-header"><i class="icon-wrench"></i> Utility</li>
	              <li id="menu_language"><a href="{$conf.rooturl_admin}language">Language Editor</a></li>
	              <li id="menu_sessionmanager"><a href="{$conf.rooturl_admin}sessionmanager">Session Manager</a></li>
	
				  
	            </ul>
	          </div><!--/.well -->
	        </div><!--/span-->
	        <div class="span10" id="container">
