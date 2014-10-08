<?php
/**
 * 
 *
 */

class Application_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract {


	public $acl;

	public function __construct()
	{
		
		// Create ACL
		$this->acl = new Zend_Acl();
		$this->acl->

		/**
		 * Add resources.
		 * Each resource have format of Controller_Name/Action_Name
		 */
		
		addResource(new Zend_Acl_Resource('index'))
			->addResource(new Zend_Acl_Resource('index/index'), 'index')
			->addResource(new Zend_Acl_Resource('index/deny'), 'index')
			->addResource(new Zend_Acl_Resource('index/login'), 'index')
			->addResource(new Zend_Acl_Resource('index/logout'), 'index')
		
		->
		addResource(new Zend_Acl_Resource('crondispatcher'))
			->addResource(new Zend_Acl_Resource('crondispatcher/index'), 'crondispatcher')
		
		->
		addResource(new Zend_Acl_Resource('show'))
			->addResource(new Zend_Acl_Resource('show/content'), 'show')
		
		->
		addResource(new Zend_Acl_Resource('motd'))
			->addResource(new Zend_Acl_Resource('motd/index'), 'motd')
		
		->
		addResource(new Zend_Acl_Resource('ajax'))
			->addResource(new Zend_Acl_Resource('ajax/rendersalesreporttable'), 'ajax')
			->addResource(new Zend_Acl_Resource('ajax/rendervideosalesreporttable'), 'ajax')
			->addResource(new Zend_Acl_Resource('ajax/renderproductstable'), 'ajax')
			->addResource(new Zend_Acl_Resource('ajax/rendervideostable'), 'ajax')
			->addResource(new Zend_Acl_Resource('ajax/getskins'), 'ajax')
			->addResource(new Zend_Acl_Resource('ajax/getuserdata'), 'ajax')
			->addResource(new Zend_Acl_Resource('ajax/ajaxerror'), 'ajax')
		
		->
		addResource(new Zend_Acl_Resource('renderajax'))
			->addResource(new Zend_Acl_Resource('ajax/renderclientpromotionstable'), 'renderajax')
			->addResource(new Zend_Acl_Resource('ajax/getpromlangsdata'), 'renderajax')
			->addResource(new Zend_Acl_Resource('ajax/renderhivecmsskinstable'), 'renderajax')
			->addResource(new Zend_Acl_Resource('ajax/renderhivecmspartnerstable'), 'renderajax')
			->addResource(new Zend_Acl_Resource('ajax/renderhivecmsuserstable'), 'renderajax')
			->addResource(new Zend_Acl_Resource('ajax/renderhivecmsrolestable'), 'renderajax')
			->addResource(new Zend_Acl_Resource('ajax/renderhivecmslanguagestable'), 'renderajax')
			->addResource(new Zend_Acl_Resource('ajax/getskinlangs'), 'renderajax')
            
        ->
		addResource(new Zend_Acl_Resource('modifyajax'))
			->addResource(new Zend_Acl_Resource('ajax/createnewpromotion'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/createnewpromotionlang'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/addnewpartner'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/addnewskin'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/addnewuser'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/addnewrole'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/addnewlanguage'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/editskin'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/editpromotion'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/sortpromotions'), 'modifyajax')
			->addResource(new Zend_Acl_Resource('ajax/edituser'), 'modifyajax')
			

		->
		addResource(new Zend_Acl_Resource('campaigns'))
			->addResource(new Zend_Acl_Resource('campaigns/index'), 'campaigns')
			->addResource(new Zend_Acl_Resource('campaigns/edit'), 'campaigns')
			->addResource(new Zend_Acl_Resource('campaigns/remove'), 'campaigns')
			->addResource(new Zend_Acl_Resource('campaigns/add'), 'campaigns')
			
		->
		addResource(new Zend_Acl_Resource('tournaments'))
			->addResource(new Zend_Acl_Resource('tournaments/index'), 'tournaments')
			->addResource(new Zend_Acl_Resource('tournaments/show'), 'tournaments')

		->
		addResource(new Zend_Acl_Resource('trnytemplates'))
			->addResource(new Zend_Acl_Resource('trnytemplates/index'), 'trnytemplates')
			->addResource(new Zend_Acl_Resource('trnytemplates/edit'), 'trnytemplates')
			->addResource(new Zend_Acl_Resource('trnytemplates/remove'), 'trnytemplates')
			->addResource(new Zend_Acl_Resource('trnytemplates/add'), 'trnytemplates')
		
		->
		addResource(new Zend_Acl_Resource('hivecms'))
			->addResource(new Zend_Acl_Resource('hivecms/partners'), 'hivecms')
			->addResource(new Zend_Acl_Resource('hivecms/skins'), 'hivecms')
			->addResource(new Zend_Acl_Resource('hivecms/users'), 'hivecms')
			->addResource(new Zend_Acl_Resource('hivecms/roles'), 'hivecms')
			->addResource(new Zend_Acl_Resource('hivecms/languages'), 'hivecms')
		
		->
		addResource(new Zend_Acl_Resource('vipshop'))
			->addResource(new Zend_Acl_Resource('vipshop/sales'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/products'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/editproduct'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/deleteproduct'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/addproduct'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/addvideo'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/bannedusers'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/customlimits'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/videosales'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/videos'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/editvideo'), 'vipshop')
			->addResource(new Zend_Acl_Resource('vipshop/deletevideo'), 'vipshop')
		
		->
		addResource(new Zend_Acl_Resource('images'))
			->addResource(new Zend_Acl_Resource('images/index'), 'images')
			->addResource(new Zend_Acl_Resource('images/show'), 'images')
			->addResource(new Zend_Acl_Resource('images/update'), 'images')
			->addResource(new Zend_Acl_Resource('images/upload'), 'images')
		
		/**
		 * User Roles
		 */
		->
		addRole(new Zend_Acl_Role('guest'))
		
		
		//for vipshop
		->
		addRole(new Zend_Acl_Role('videomanageruser'), 'guest')
		
		->
		addRole(new Zend_Acl_Role('superuser'), 'guest')
        
        ->
        addRole(new Zend_Acl_Role('motduser'), 'guest')
		
		->
        addRole(new Zend_Acl_Role('promotionuser'), 'guest')
		
		->
        addRole(new Zend_Acl_Role('motdpromouser'), array('promotionuser','motduser'))
		
		
		/**
		 * Set privileges for each role
		 */
		->
		allow('guest', array(
			'index/login',
			'index/deny',
			'show',
			'crondispatcher',
			'ajax/ajaxerror',
		))
		
		->
		allow('videomanageruser', array(
			'index',
			'vipshop/videosales',
			'vipshop/videos',
			'vipshop/editvideo',
			'vipshop/addvideo',
			'vipshop/deletevideo',
			'ajax/rendervideosalesreporttable',
			'ajax/rendervideostable',
			
		))
		
		->
		allow('superuser', array(
			'motd',
			'index',
			'ajax',
			'renderajax',
			'modifyajax',
			'campaigns',
			'tournaments',
			'trnytemplates',
			'vipshop',
			'hivecms'
		))
        
        ->
        allow('motduser', array(
            'index',
            'motd'
        ))
		
		->
        allow('promotionuser', array(
            'images',
            'ajax/getskinlangs',
            'ajax/createnewpromotion',
            'ajax/createnewpromotionlang',
            'ajax/renderclientpromotionstable',
            'ajax/getpromlangsdata',
            'ajax/editpromotion',
            'ajax/sortpromotions',
        ));

	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {

		$auth = Zend_Auth::getInstance();
		$isAllowed = false;

		$controller = $request->getControllerName();
		$action = $request->getActionName();
		
		// Generate the resource name
		$resourceName = $controller . '/' . $action;
		
		// Don't block errors
		if ($resourceName == 'error/error')
			return;
		
		$resources = $this->acl->getResources();
		
		if (! in_array($resourceName, $resources)) {
			$request->setControllerName('error')
			->setActionName('error')
			->setDispatched(true);
			throw new Zend_Controller_Action_Exception('This page does not exist / missing ACL entrie?', 404);
			return;
		}
		
		// Check if user can access this resource or not
		$isAllowed = $this->acl->isAllowed(Zend_Registry::get('role'), $resourceName);

		// Forward user to access denied or login page if this is guest
		if (! $isAllowed) {
				
			if (! Zend_Auth::getInstance()->hasIdentity()) {
		
				$forwardAction = 'login';
				$forwardMessage = "Your connection timeout, please login again!";
			} else {

				$forwardAction = 'deny';
				$forwardMessage = "You don't have right to do this action!";
			}
			
			if ( $controller == "ajax" ) {
				$request->setControllerName("ajax")
				->setActionName("ajaxerror")
				->setParam("msg", $forwardMessage)
				->setDispatched(true);
			} else {
				$request->setControllerName('index')
				->setActionName($forwardAction)
				->setDispatched(true);
			}
		}
	}
}