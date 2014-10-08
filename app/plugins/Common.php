<?php
/**
 * Common, application-wide methods

 */

class Application_Plugin_Common extends Zend_Controller_Plugin_Abstract {


	/**
	 *
	 * Login user
	 *
	 */
	static public function loginUser($username, $password)
	{
		$authAdapter = new Application_Plugin_SimpleAuthAdapter($username, $password);

		$auth = Zend_Auth::getInstance();

		$result = $auth->authenticate($authAdapter);

		if ($result->isValid()){
			return true;
		}

		// not ok
		return false;
	}


	/**
	 * get full base url (http://www.example.com/test)
	 */
	static public function getFullBaseUrl($include_base = true)
	{
		$front = Zend_Controller_Front::getInstance();
		$view = new Zend_View();

		if ($include_base){
			$base_url = $view->baseUrl();
		}else{
			$base_url = '';
		}

		$url = $front->getRequest()->getScheme() .'://'.$front->getRequest()->getHttpHost().$base_url;

		return $url;
	}


	/**
	 * Render timestamp as human-readable date
	 */
	static public function tsToHuman($timestamp)
	{
		return date(Zend_Registry::get('config')->date_format, $timestamp);
	}


	/**
	 * Render mysql date
	 */
	static public function renderMysqlDate($mysqldate, $short = true)
	{
		$phpdate = strtotime($mysqldate);

		$format = $short ? Zend_Registry::get('config')->date_format_short : Zend_Registry::get('config')->date_format;

		return date($format, $phpdate);
	}

	/**
	 *
	 * log error messages to file
	 */
	public static function log($messages)
	{
		$writer = new Zend_Log_Writer_Stream('error.log');
		$log = new Zend_Log($writer);

		$uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();

		$backTrace = debug_backtrace();
		if (isset($backTrace[2]['class'])) {
			$class_method = $backTrace[2]['class'] . "::" . $backTrace[2]['function'] . "()";
		} else {
			$class_method = "";
		}

		if (is_array($messages)){
			foreach ($messages as $message){
				$log->log($message, Zend_Log::ERR, array('timestamp' => Application_Plugin_Common::now(), 'class_method' => $class_method));
			}

		}else{
			$log->log($messages, Zend_Log::ERR, array('timestamp' => Application_Plugin_Common::now(), 'class_method' => $class_method));
		}

	}



	/**
	 *
	 * Returns mysql datetime for current time
	 *
	 */
	public static function now() {
		return date('Y-m-d H:i:s', time());
	}




	/**
	 *
	 * Export to csv tmp/browser
	 */
	static public function exportToCsv($data, $fname)
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		$filename = PUBLIC_PATH . '/tmp/' .$fname.'.csv';
		
		//var_dump($data);
		// build csv header
		$header = array();
		if (!empty($data)){
			$head = $data->fetch();
			foreach ($head as $key => $field){
				$header[$key] = $key;
			}
		}
		
		if (isset($head) && !empty($head)) {
			$output = fopen($filename, 'w');
		} else {
			
			// output headers so that the file is downloaded rather than displayed
			//header_remove();
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=data.csv');
			
			$output = fopen('php://output', 'w');
			readfile($filename);
			
			die;
		}
		
		fputcsv($output, $header);
		
		fputcsv($output, $head);

		while ($row = $data->fetch()){
			//var_dump($row);
			fputcsv($output, $row);
		}

		Application_Plugin_Common::deleteOldTmpFiles();
		
		die;
	}

	

	/**
	 *
	 * delete old files from tmp folder
	 */
	public function deleteOldTmpFiles($seconds_old = 240){
	
		// delete old files inside tmp folder
		foreach (glob(PUBLIC_PATH . '/tmp/*') as $file) {
			if (is_file($file) && filemtime($file) < time() - $seconds_old) {
				@unlink($file);
			}
		}
	
		return;
	}
	




}
