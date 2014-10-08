<?php
/**
 * Zend Bootstrap
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	private $_view;
	
	protected function _initRewrite() {
		$front = Zend_Controller_Front::getInstance();
		$router = $front->getRouter();

		$router->addRoute('all', 
			new Zend_Controller_Router_Route('*', 
				array(
					'controller' => 'index',
					'action' => 'index')
			)
		);
	}
	public function __construct($application)
	{
		parent::__construct($application);
		
		// get view resources
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');

		$this->_view = $layout->getView();
		
		// js tmps path
		$this->_view->addScriptPath('js/tmps/');
		
		// add custom view helpers paths
		$this->_view->addHelperPath(APPLICATION_PATH . '/views/helpers/');

		// setup database for boostrap use
		$db = $this->getPluginResource('db')->getDbAdapter('db1');
		
		// Firebug DB Profiler
		if (isset($_GET['profiler']) && $_GET['profiler'] == 'firebug') {
			$profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
			$profiler->setEnabled(true);
			$db->setProfiler($profiler);
		}
		
		// Force the initial connection to handle error relating to caching etc.
		try {
			$db->getConnection();
		} catch (Zend_Db_Adapter_Exception $e) {
			echo $e->getMessage();
			die;
		} catch (Zend_Exception $e) {
			echo $e->getMessage();
			die;
		}

		Zend_Db_Table::setDefaultAdapter($db);
		
		// Set user's 'role' as a global
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			Zend_Registry::set('role', Zend_Auth::getInstance()->getStorage()->read()->role);
		}
		else
		{
			Zend_Registry::set('role', 'guest');
		}
		
	}


	/**
	 * Init, security etc.
	 */
	protected function _initAutoload() {

		// load action helpers
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers');
		

		$controller = Zend_Controller_Front::getInstance();

		// load ACL security
		//$controller->registerPlugin(new Application_Plugin_AccessCheck());

		// load global notifications
		$controller->registerPlugin(new Application_Plugin_GlobalDisplay());
		
	}


	/**
	 * Init common arrays
	 */
	protected function _initArrays() {

		$config = new stdClass();
		
		// pagination settings
		$config->pagination_limit = 10;
		$config->date_format = 'd-m-Y H:i';
		$config->date_format_short = 'd-m-Y';
		
		Zend_Registry::set('config', $config);

	}
	
}

