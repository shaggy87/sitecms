<?php
/**
 * Autoload on every page
 */

class Application_Plugin_GlobalDisplay extends Zend_Controller_Plugin_Abstract {

	private $view;


	/**
	 *
	 * Init $this->_view
	 */
	public function __construct()
	{
		$this->view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
	}


	/**
	 *
	 * Create standard display vars for each view
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {

		$controller_name = $request->getControllerName();
		$action_name = $request->getActionName();

	}


}