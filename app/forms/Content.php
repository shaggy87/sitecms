<?php
/**
 * Form
 */

class Application_Form_Content extends Zend_Form
{
	private $lang;
	
	private $role;
    
    private $skinid;
	
	private $partnerskins;
	
	private $skinLanguages;
	
	private $day;
	
	
	public function __construct($skinid, $lang, $role, $day, $partnerskins = array(), $skinLanguages = array())
	{
		$this->lang = $lang;
		$this->role = $role;
        $this->skinid = $skinid;
		$this->partnerskins = $partnerskins;
		$this->skinLanguages = $skinLanguages;
		$this->day = $day;
		parent::__construct();
	}
	

	/**
	 *
	 * form
	 *
	 */
	public function init()
	{
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
		$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
		$caUrl = $baseUrl .'/'. $controller .'/'. $action;

		// form name matches class extension
		$cname = explode('_', get_class());
		$formname = end($cname);
        
		// use template file
		$this->setDecorators( array(
				array('ViewScript', array('viewScript' => 'forms/Common.phtml'))));

		$this->setName($formname);
		$this->setMethod('post');
		$this->setAction('');
		
		$skins = array();
		foreach ($this->partnerskins as $k=>$row) {
			$skins[$row["skinid"]] = $row["name"];
		}

        $skinid = new Zend_Form_Element_Select('skinid', array('onchange' => 'changeLanguage("'.$caUrl.'", true);'));
        $skinid
        ->setDecorators(array('ViewHelper', 'Errors'))
        ->setLabel('Skin')
        ->setRequired(true)
        ->setMultiOptions(
              $skins
         )
        ->setValue($this->skinid)
        ->setAttrib('class', 'form-control');
        
		$langs = array();
		if ($this->skinLanguages) {
			foreach ($this->skinLanguages as $k=>$row) {
				$langs[$row["short_lang"]] = $row["lang"];
			}
		}

		// fields
		$language = new Zend_Form_Element_Select('language', array('onchange' => 'changeLanguage("'.$caUrl.'");'));
		$language
		->setDecorators(array('ViewHelper', 'Errors'))
		->setMultiOptions($langs)
		->setLabel('Select language')
		->setValue($this->lang)
		->setRequired(true)
		->setAttrib('class', 'form-control');
		
		$days = array(
			7 => "All",
			1 => "Monday",
			2 => "Tuesday",
			3 => "Wednesday",
			4 => "Thursday",
			5 => "Friday",
			6 => "Saturday",
			0 => "Sunday",
		);
		// fields
		$period = new Zend_Form_Element_Select('period', array('onchange' => 'changeLanguage("'.$caUrl.'");'));
		$period
		->setDecorators(array('ViewHelper', 'Errors'))
		->setMultiOptions($days)
		->setLabel('Select Day of appearance')
		->setValue($this->day)
		->setRequired(true)
		->setAttrib('class', 'form-control');
			
		$content = new Zend_Form_Element_Textarea('content');
		$content
		->setDecorators(array('ViewHelper', 'Errors'))
		->setAttrib('COLS', '')
		->setAttrib('ROWS', '10')
		->setLabel('Content')
		->setAttrib('class', 'form-control');

		$submit = new Zend_Form_Element_Submit('submitbtn');
		$submit
		->setDecorators(array('ViewHelper'))
		->setLabel('Save')
		->setAttrib('class', 'submit btn btn-default');
		
		$user_roles = new Zend_Form_Element_Select('viprank', array('onchange' => 'changeLanguage("'.$caUrl.'");'));
		$user_roles
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('User Role')
		->setRequired(true)
		->setMultiOptions(
			!isset(Zend_Auth::getInstance()->getStorage()->read()->partner_name) ? 
		      	Zend_Registry::get('user_roles_motd') : (Zend_Registry::isRegistered(strtolower(Zend_Auth::getInstance()->getStorage()->read()->partner_name . '_user_roles_motd')) ? 
		      		Zend_Registry::get(strtolower(Zend_Auth::getInstance()->getStorage()->read()->partner_name).'_user_roles_motd') : Zend_Registry::get('user_roles_motd'))
         )
		->setValue(isset($this->role) ? $this->role : 0)
		->setAttrib('class', 'form-control');
		
		$this->addElements(array($skinid, $language, $content, $submit, $user_roles, $period));

	}

}

