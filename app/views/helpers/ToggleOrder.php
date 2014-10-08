<?php

class Zend_View_Helper_ToggleOrder extends Zend_View_Helper_Abstract {


	public function ToggleOrder($order_by) {

		$uri = $this->view->baseUrl().'/';
		
		$request = Zend_Controller_Front::getInstance()->getRequest();
		
		$params = $request->getParams();
		
		foreach ($params as $key => $val){
			if ($key === 'module' || $key === 'order_sort' || $key === 'order_by' || $key === 'page') continue;
			
			if ($key === 'controller' || $key === 'action'){
				@$uri .=  $val . '/';
			}else{
				//var_dump($key, $val);die;
				@$uri .= $key . '/' . $val . '/';
			}
		}
		
		// order toggle
		$uri .= ($request->getParam('order_sort') == 'asc' ? 'order_sort/desc' : 'order_sort/asc');
		
		$uri .= '/page/1/order_by/'.$order_by;
		
		return $uri;
	}

}