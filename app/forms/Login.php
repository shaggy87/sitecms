<?php
/**
 * Login Form
 */

class Application_Form_Login extends Zend_Form
{

	/**
	 *
	 * Login page form
	 *
	 */
	public function init()
	{
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

		// form name matches class extension
		$cname = explode('_', get_class());
		$formname = end($cname);

		// use template file
		$this->setDecorators( array(
				array('ViewScript', array('viewScript' => 'forms/Login.phtml'))));

		$this->setName($formname);
		$this->setMethod('post');
		$this->setAction('');

		// fields
			
		$name = new Zend_Form_Element_Text('name');
		$name
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Username or email')
		->addFilter('StringToLower')
		->setErrorMessages(array('Enter your username'))
		->setAttrib('class', 'form-control')
		->setRequired(true);

		$password = new Zend_Form_Element_Password('password');
		$password
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Password')
		->setErrorMessages(array('Enter your password'))
		->setAttrib('class', 'form-control')
		->setRequired(true);

		$login = new Zend_Form_Element_Submit('loginbtn');
		$login
		->setDecorators(array('ViewHelper'))
		->setLabel('Sign In')
		->setAttrib('class', 'submit btn btn-default');

		$this->addElements(array($name, $password, $login));

	}

}

