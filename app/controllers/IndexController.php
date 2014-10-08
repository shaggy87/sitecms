<?php
/**
 * Default Controller & Auth
 */

class IndexController extends Zend_Controller_Action
{

	/**
	 *
	 * Main Page
	 */
	public function indexAction()
	{
		$request = $this->getRequest();
		$this->view->sitedata = array(
			"siteId" => 1,
			"siteUrl" => "http://sitecms.intra",
			"title" => "Site Title",
			"Logo" => "",
			"menu" => array(
				0 => array(
					"title" => "Home",
					"url" => "",
				),
				1 => array(
					"title" => "About",
					"url" => "about",
				),
				2 => array(
					"title" => "Products",
					"url" => "products",
				),
				3 => array(
					"title" => "Contact",
					"url" => "contact",
				),
			),
		);
		
		$pages = array(
			'/' => array(
				"title" => "Home",
				"short_desc" => "This is short description",
				"content" => "This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content!",
				"sections" => array(
					0 => array(
						"type" => 1,
						"templete" => "template1.phtml",
						"articles" => array(
							0 => array(
								"title" => "This is article title",
								"content" => "This is article content! This is article content! This is article content! This is article content!",
								"xref" => array(
									0 => array(
										"contentid" => 1,
										"title" => "slika 1",
										"path" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
										"link" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
									)
								)
							)
						),
					),
				),
			),
			'/about' => array(
				"title" => "About",
				"short_desc" => "This is short description",
				"content" => "This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content!",
				"sections" => array(
					0 => array(
						"type" => 1,
						"templete" => "template1.phtml",
						"articles" => array(
							0 => array(
								"title" => "This is article title",
								"content" => "This is article content! This is article content! This is article content! This is article content!",
								"xref" => array(
									0 => array(
										"contentid" => 1,
										"title" => "slika 1",
										"path" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
										"link" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
									)
								)
							),
						),
					),
				),
			),
			'/products' => array(
				"title" => "Products",
				"short_desc" => "This is short description",
				"content" => "This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content!",
				"sections" => array(
					0 => array(
						"type" => 1,
						"templete" => "template1.phtml",
						"articles" => array(
							0 => array(
								"title" => "This is article title",
								"content" => "This is article content! This is article content! This is article content! This is article content!",
								"xref" => array(
									0 => array(
										"contentid" => 1,
										"title" => "slika 1",
										"path" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
										"link" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
									)
								)
							),
							1 => array(
								"title" => "This is article 2 title",
								"content" => "This is article 2 content! This is article 2 content! This is article 2 content! This is article 2 content!",
								"xref" => array(
									0 => array(
										"contentid" => 1,
										"title" => "slika 2",
										"path" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
										"link" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
									)
								)
							)
						),
					),
				),
			),
			'/contact' => array(
				"title" => "Contact",
				"short_desc" => "This is short description",
				"content" => "This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content! This is page content!",
				"sections" => array(
					0 => array(
						"type" => 1,
						"templete" => "template1.phtml",
						"articles" => array(
							0 => array(
								"title" => "This is article title",
								"content" => "This is article content! This is article content! This is article content! This is article content!",
								"xref" => array(
									0 => array(
										"contentid" => 1,
										"title" => "slika 1",
										"path" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
										"link" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
									)
								)
							),
							1 => array(
								"title" => "This is article 2 title",
								"content" => "This is article 2 content! This is article 2 content! This is article 2 content! This is article 2 content!",
								"xref" => array(
									0 => array(
										"contentid" => 1,
										"title" => "slika 2",
										"path" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
										"link" => "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Slika_nije_dostupna.svg/600px-Slika_nije_dostupna.svg.png",
									)
								)
							)
						),
					),
				),
			),
		);
		
		$this->view->sitedata["page"] = isset($pages[$_SERVER["REQUEST_URI"]]) ? $pages[$_SERVER["REQUEST_URI"]] : "/";
	}


	/**
	 *
	 * Login page
	 */
	public function loginAction()
	{
		$this->_helper->_layout->setLayout('layout_login');
		
		$request = $this->getRequest();
		
		if (Zend_Auth::getInstance()->hasIdentity()){
			$this->redirect('');
		}
		
		$form = new Application_Form_Login();
		$this->view->login_form = $form;
		
		if ($request->isPost() && $form->isValid($request->getParams())) {
			
			$ret = Application_Plugin_Common::loginUser($request->getParam('name'), $request->getParam('password'));
			
			if ($ret){
				$this->redirect('');
			}else{
				$form->getElement('name')->setErrors(array('Invalid username or password'));
			}
			
		}
		
	}


	/**
	 *
	 * Logout & Destroy identity
	 */
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();

		// flush url
		$this->redirect('');
	}

	/**
	 *
	 * Access denied
	 */
	public function denyAction()
	{

	}

}