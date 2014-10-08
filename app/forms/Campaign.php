<?php
/**
 * Form
 */

class Application_Form_Campaign extends Zend_Form
{

	public $record;
	
	public function __construct($id = null)
	{
		if ($id){
			$Campaign = new Application_Model_Campaigns();
			$this->record = $Campaign->get($id);
		}
		
		parent::__construct();
	}
	
	
	/**
	 *
	 * form
	 *
	 */
	public function init()
	{
		$cron = Application_Plugin_Cron::init(isset($this->record['expression']) ? $this->record['expression'] : '0 0 * * * *');
		
		// form name matches class extension
		$cname = explode('_', get_class());
		$formname = end($cname);

		// use template file
		$this->setDecorators( array(
				array('ViewScript', array('viewScript' => 'forms/Common.phtml'))));

		$this->setName($formname);
		$this->setMethod('post');
		$this->setAction('');
		
		$min_array = array();
		for ($i = 0; $i <= 55; $i = $i + 5) $min_array[$i] = $i;
		
		$hour_array = array();
		for ($i = 0; $i <= 23; $i++) $hour_array[$i] = $i;
		
		$day_array = array();
		for ($i = 1; $i <= 31; $i++) $day_array[$i] = $i;
		
		$week_array = array(
				'1' => 'Monday',
				'2' => 'Tuesday',
				'3' => 'Wednesday',
				'4' => 'Thursday',
				'5' => 'Friday',
				'6' => 'Saturday',
				'7' => 'Sunday',
				);

		// fields
		$campaign_name = new Zend_Form_Element_Text('campaign_name');
		$campaign_name
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Campaign Name')
		->setRequired(true)
		->setValue(isset($this->record['campaign_name']) ? $this->record['campaign_name'] : '')
		->setAttrib('class', 'form-control');
		
		$start_date = new Zend_Form_Element_Text('start_date');
		$start_date
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Start Date')
		->setRequired(true)
		->setValue(isset($this->record['start_date']) ? Application_Plugin_Common::renderMysqlDate($this->record['start_date']) : date(Zend_Registry::get('config')->date_format_short))
		->setAttrib('class', 'form-control datepicker');
		
		$end_date = new Zend_Form_Element_Text('end_date');
		$end_date
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('End Date')
		->setRequired(true)
		->setValue(isset($this->record['end_date']) ? Application_Plugin_Common::renderMysqlDate($this->record['end_date']) : date(Zend_Registry::get('config')->date_format_short, strtotime("+1 month", time())))
		->setAttrib('class', 'form-control datepicker');
		
		
		$TrnyTempltes = new Application_Model_TrnyTemplates();
		$available_trny_templates = $TrnyTempltes->getAll();
		
		foreach ($available_trny_templates as $trny_template) {
			$trny_templates_options[$trny_template['id']] = $trny_template['name'];
		}
		
		$trny_template = new Zend_Form_Element_Select('trny_template');
		$trny_template
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Tournament Template')
		->setRequired(true)
		->setMultiOptions($trny_templates_options)
		->setValue(isset($this->record['trny_template']) ? $this->record['trny_template'] : 0)
		->setAttrib('class', 'form-control');
		

		$mins = new Zend_Form_Element_Select('mins');
		$mins
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Minues')
		->setAttrib('class', 'form-control')
		->setMultiOptions($min_array)
		->setRequired(true)
		->setValue($cron->getExpression(0) > 0 ? $cron->getExpression(0) : 0)
		->setAttrib('class', 'form-control');
		
		$hrs = new Zend_Form_Element_Multiselect('hrs');
		$hrs
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Hours')
		->setAttrib('class', 'form-control')
		->setMultiOptions($hour_array)
		->setValue(explode(',', $cron->getExpression(1)))
		->setAttrib('class', 'form-control');
		
		$days = new Zend_Form_Element_Multiselect('days');
		$days
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Days')
		->setAttrib('class', 'form-control')
		->setMultiOptions($day_array)
		->setValue(explode(',', $cron->getExpression(2)))
		->setAttrib('class', 'form-control');
		
		$week = new Zend_Form_Element_Multiselect('week');
		$week
		->setDecorators(array('ViewHelper', 'Errors'))
		->setLabel('Weekdays')
		->setAttrib('class', 'form-control')
		->setMultiOptions($week_array)
		->setValue(explode(',', trim($cron->getExpression(4), 'L')))
		->setAttrib('class', 'form-control');
		
		$last_weekday_of_month = new Zend_Form_Element_Checkbox('last_weekday_of_month');
		$last_weekday_of_month
		->setDecorators(array('ViewHelper', 'Errors'))
		->setValue(strpos($cron->getExpression(4), 'L') !== FALSE ? 1 : 0)
		->setLabel('Last given weekday of a month')
		->setCheckedValue("1")
		->setUncheckedValue("0");
		
		$submit = new Zend_Form_Element_Submit('submitbtn');
		$submit
		->setDecorators(array('ViewHelper'))
		->setLabel('Save')
		->setAttrib('class', 'submit btn btn-default');

		$this->addElements(array($campaign_name, $start_date, $end_date, $trny_template, $hrs, $mins, $days, $week, $last_weekday_of_month, $submit));

	}
	

	public function getCronExpression()
	{
		$month = '*';
		
		if ($this->getValue('mins')){
			$mins = $this->getValue('mins');
		}else{
			$mins = '0';
		}
	
		if (is_array($this->getValue('hrs'))){
			$hrs = implode(',', $this->getValue('hrs'));
		}else{
			$hrs = '*';
		}
	
		if (is_array($this->getValue('days'))){
			$days = implode(',', $this->getValue('days'));
		}else{
			$days = '*';
		}
	
		$LW = '';
		if ($this->getValue('last_weekday_of_month')){
			$LW = 'L';
			$days = '*';
		}
		
		if (is_array($this->getValue('week'))){
			$week = implode(',', $this->getValue('week'));
		}else{
			$week = '*';
		}
		
		$expression = $mins .' '. $hrs .' '. $days .' '. $month .' '. $week.$LW .' *';
	
		return $expression;
	}


}

