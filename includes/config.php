<?php
//	------------------------------
// 		CONSTANT DEFINE 
//	------------------------------
define ('DEBUG', 1);
define ('TABLE_PREFIX', 'lit_');


$site_path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
define ('SITE_PATH', $site_path);


	

//Development Phase
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);
ini_set('session.name', 'LITPIHASH');
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
date_default_timezone_set('Asia/Ho_Chi_Minh');


//configuration variables
$conf = array();
$conf['db']['host'] = 'localhost';
$conf['db']['name'] = 'litpifw';
$conf['db']['user'] = 'root';
$conf['db']['pass'] = 'root';

$conf['host'] = 'localhost/litpifw';
$conf['rooturl'] = 'http://' . $conf['host'] . '/';
$conf['rooturl_admin'] = 'http://' . $conf['host'] . '/admin/';

$conf['defaultLang'] = 'en';
$conf['usingGZIP'] = true;

/**
   * Sets the SMTP hosts.  All hosts must be separated by a
   * semicolon.  You can also specify a different port
   * for each host by using this format: [hostname:port]
   * (e.g. "smtp1.example.com:25;smtp2.example.com").
   * Hosts will be tried in order.
   * @var string
   */
$conf['smtp']['enable'] = true;
$conf['smtp']['host'] = 'smtp.gmail.com';
$conf['smtp']['username'] = '';
$conf['smtp']['password'] = '';






?>