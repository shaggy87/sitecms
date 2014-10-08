<?php

// Define application environment (vipshop | pokerbo | pokerbolocaldb)
define('APPLICATION_CONTEXT', 'vipshop');


// Define application environment (production | development)
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Check if htaccess file exists 
//if (!file_exists('.htaccess')) {
//	echo "Error: File .htaccess not found. Please check if you uploaded this file.";
//	die;
//}

// Define path to public directory
defined('PUBLIC_PATH')
	|| define('PUBLIC_PATH', realpath(dirname(".")));

// Define path to application directory  
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/app'));
    
// Ensure library is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(dirname(__FILE__) . '/zend'),
	get_include_path(),
)));

// Zend_Application
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
	APPLICATION_ENV,
	APPLICATION_PATH . '/configs/application.php'
);

$application->bootstrap()
			->run();