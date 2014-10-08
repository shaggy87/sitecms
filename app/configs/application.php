<?php 

$cfg = array(
		'resources' => array(
				'db' => array (
						'adapter' => 'PDO_MYSQL',
						'params' => array()),


				'frontController' => array(
						'controllerDirectory' => APPLICATION_PATH . '/controllers',
						'params' => array(
								'displayExceptions' => 0)),

				'layout' => array(
				),

		),

		'phpSettings' => array(
				'date' => array(
						'timezone' => 'UTC')
		),

		'bootstrap' => array(
				'path' => APPLICATION_PATH . '/Bootstrap.php',
				'class' => 'Bootstrap'),

		'appnamespace' => 'Application',

);

switch (APPLICATION_CONTEXT) {
	
	case 'pokerbo':
		
		$cfg['resources']['db']['params'] = array(
								'host' => '192.168.37.185',
								'dbname' => 'poker_qa',
								'username' => 'pokerbonew',
								'password' => '9BfPLMtvDjqe',
								'charset' => 'utf8');
		break;
		
	case 'pokerbolocaldb':
		
		$cfg['resources']['db']['params'] = array(
		'host' => '192.168.1.5',
		'dbname' => 'pokerbo',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8');
		break;
	
	case 'vipshop':

		$cfg['resources']['db']['params'] = array(
		'host' => 'localhost',
		'dbname' => 'vipshop',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8');

		break;
		
	/*case 'vipshop':

		$cfg['resources']['db']['params'] = array(
		'host' => 'localhost',
		'dbname' => 'newdesign_common',
		'username' => 'vegas365',
		'password' => 'a49Xw_N3b',
		'charset' => 'utf8');

		break;
	 * 
	 */
}

return $cfg;


