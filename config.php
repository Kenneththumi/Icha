<?php
if($_SERVER['HTTP_HOST']!='localhost'){
    
	//remote settings
	define('ABSOLUTE_PATH',dirname(__FILE__).'/');
	define('SITE_PATH','http://'.$_SERVER['SERVER_NAME'].'/');
	define('PLUGIN_PATH','http://'.$_SERVER['SERVER_NAME'].'/plugins');
	define('MEDIA_PATH','http://'.$_SERVER['SERVER_NAME'].'/media/');
	define('ABSOLUTE_MEDIA_PATH',dirname(__FILE__).'/media/');
	define('STYLES_PATH','http://'.$_SERVER['SERVER_NAME'].'/styles/');
	define('SITENAME','ICHA Kenya | International Center for Humanitarian Affairs');
        define('MAILFROM','noreply@icha.net');
        define('MAILADMIN','info@icha.net');
        define('MAILFINANCE','info@icha.net');
        define('DS',DIRECTORY_SEPARATOR);
}
else{
	//local settings
	define('ABSOLUTE_PATH',$_SERVER['DOCUMENT_ROOT'].'/icha/');
	define('SITE_PATH','http://'.$_SERVER['SERVER_NAME'].'/icha/');
	define('PLUGIN_PATH','http://'.$_SERVER['SERVER_NAME'].'/icha/plugins');
	define('MEDIA_PATH','http://'.$_SERVER['SERVER_NAME'].'/icha/media/');
        define('ABSOLUTE_MEDIA_PATH',$_SERVER['DOCUMENT_ROOT'].'/icha/media/');
	define('STYLES_PATH','http://'.$_SERVER['SERVER_NAME'].'/icha/styles/');
	define('SITENAME','ICHA Kenya | International Center for Humanitarian Affairs');
        define('MAILFROM','noreply@icha.net');
        define('MAILADMIN','info@icha.net');
        define('MAILFINANCE','info@icha.net');
         define('DS',DIRECTORY_SEPARATOR);
}

define('LOAD_POINT',1);

class M3Config{
    
	//local settings for the local computer
	public $localdb = 'ichadb';	
	public $localhost = 'localhost';
	public $localuser = 'root';
	public $localpassword = '';
	
	//remote settings
	public $remotedb = '896341_icha';	
	public $remotehost = 'mysql51-066.wc1.ord1.stabletransit.com';
	public $remoteuser = '896341_icha';
	public $remotepassword = 'Admin@icha2015';
}
