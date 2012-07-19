<?php
	define('GROUPID_GUEST', 0);
	define('GROUPID_ADMIN', 1);
	define('GROUPID_MODERATOR', 5);
	define('GROUPID_MEMBER', 20);
	
	

	//format: $p[groupid] = array('{controllerGroup}' => array ('{controller}_{action}'));
		
	$groupPermisson[GROUPID_GUEST] = array(
		'site'		=>	array(
			'index_*',
			'login_*',
			'notfound_*',
			'notpermission_*',
			'install_*',
			'captcha_*',
		)
	);
	    
	
	
	$groupPermisson[GROUPID_ADMIN] = array(
		'site'		=>	array(
			'index_*',
			'logout_*',
			'notfound_*',
			'notpermission_*',
			'captcha_*',
		),
		'admin'		=>	array(
			'index_*',
			'log_*',
			'user_*',
			'language_*',
			'sessionmanager_*',
		),
	);
	
	
	
	
	
	



?>
