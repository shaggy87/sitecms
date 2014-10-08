<?php
/**
 * Form
 */

class Application_Form_TrnyTemplate extends Zend_Form
{

	/**
	 *
	 * form
	 *
	 */
	
	public $record;
	
	
	public function __construct($id = null)
	{
		if ($id){
			$TrnyTemplates = new Application_Model_TrnyTemplates();
			$this->record = $TrnyTemplates->get($id);
		}
	
		parent::__construct();
	}
	
	
	public function init()
	{
		// form name matches class extension
		$cname = explode('_', get_class());
		$formname = end($cname);

		// use template file
		$this->setDecorators( array(
				array('ViewScript', array('viewScript' => 'forms/Common.phtml'))));

		$this->setName($formname);
		$this->setMethod('post');
		$this->setAction('');
		
		// fields
		$title = new Zend_Form_Element_Text('name');
		$title
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Name')
		->setRequired(true)
		->setValue(isset($this->record['name']) ? $this->record['name'] : '')
		->setAttrib('class', 'form-control');
	
		$submit = new Zend_Form_Element_Submit('submitbtn');
		$submit
		->setDecorators(array('ViewHelper'))
		->setLabel('Save')
		->setAttrib('class', 'submit btn btn-default');

		$this->addElements(array($title, $submit));

	}


}

