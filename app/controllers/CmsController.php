<?php
/**
 * Cms
 */

class CmsController extends Zend_Controller_Action
{

	/**
	 *
	 * Main Page
	 */
	public function indexAction()
	{
		$this->_helper->_layout->setLayout('layout_cms');
	}


}