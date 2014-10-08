<?php
/**
 * Default Error Controller
 * 
 */

class ErrorController extends Zend_Controller_Action
{

	public function errorAction()
	{
		$this->_helper->_layout->setLayout('errors');
		
		$this->_helper->viewRenderer->setNoRender(true);

		// default application error
		$this->getResponse()->setHttpResponseCode(500);
		$this->view->message = $this->view->translate('Application error');
		
		// log errors
		$logtext = "\n------------------------------------------------------------\n";
		
		$errors = $this->_getParam('error_handler');
		
		if (isset($errors->type)){
			switch ($errors->type) {
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
					// 404 error -- controller or action not found
					$this->getResponse()->setHttpResponseCode(404);
					$this->view->message = $this->view->translate('404 - Page not found');
					break;
			}
		}
		
		$logtext .= $this->view->message;
		$logtext .= "\n";
		
		if (isset($errors->exception)){
			$logtext .= (isset($errors->exception->information) ? $errors->exception->information : '');
			$logtext .= "\n";
			$logtext .= $errors->exception->getMessage();
			$logtext .= "\n";
			$logtext .= $errors->exception->getTraceAsString();
		}

		// conditionally display exceptions
		if (APPLICATION_ENV != 'production' || isset($errors->exception)){
			$this->view->exception = $errors->exception;
		}
			
		if (isset($errors->request)) {
			$this->view->request   = $errors->request;
			
			$logtext .= var_export($errors->request->getParams(), true);
			$logtext .= "\n";
			
		} else {
			$this->view->request   = '';
		}
		
		// log errors
		Application_Plugin_Common::log($logtext);
	}


}

