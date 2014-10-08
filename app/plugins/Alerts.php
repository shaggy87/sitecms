<?php
/**
 * Alerts flash messenger plugin
 */

class Application_Plugin_Alerts
{


	/**
	 *
	 * Success notification
	 * @param string $message
	 */
	public static function success($message, $sticky = 'on') {
		Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->addMessage(array('level' => 'success', 'message' => addslashes($message), 'animation' => $sticky));
	}

	/**
	 *
	 * Error notification
	 * @param string $message
	 */
	public static function error($message, $sticky = 'off') {
		Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->addMessage(array('level' => 'danger', 'message' => addslashes($message), 'animation' => $sticky));
	}

	/**
	 *
	 * Generic notification
	 * @param string $message
	 */
	public static function info($message, $sticky = 'off') {
		Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->addMessage(array('level' => 'info', 'message' => addslashes($message), 'animation' => $sticky));
	}

	/**
	 * Get messages from current session (optional) and from previous session and return them
	 * Flushes the current session messages
	 *
	 * @param bool $current include messages from current session
	 */
	public static function getMessages($current = true) {
		$flashMessages = array();
		$flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		
		// check for current messages
		// if found, display them now and flush the current message array
		// therefore, all messages intended to be shown on next page-refresh, should be near the redirect
		// and not in the parts which include other functionalities on the same page
		if ($current && $flashMessenger->hasCurrentMessages()) {
			$flashMessages = $flashMessenger->getCurrentMessages();
			$flashMessenger->clearCurrentMessages();
		}
		if ($flashMessenger->hasMessages()) {
			$flashMessages = array_merge($flashMessages, $flashMessenger->getMessages());
		}

		return $flashMessages;
		
	}

}

?>
