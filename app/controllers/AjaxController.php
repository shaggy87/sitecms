<?php
/**
 * Default Controller & Auth
 */

class AjaxController extends Zend_Controller_Action
{

	public function init()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}
	
	public function ajaxerrorAction()
	{
		$request = $this->getRequest();
		
		echo $this->_helper->json(
			array(
				"status" => 2,
				"error"  => $request->msg,
			)
		);
		return;
	}

	/**
	 *
	 * rendering table
	 */
	public function rendersalesreporttableAction()
	{
		$request = $this->getRequest();
		$sort = $request->getParam('sort', array());

		$page = empty($sort['page']) ? 1 : $sort['page'];

		$filter = trim($request->getParam('filter', false));
		$level = trim($request->getParam('level', false));
		$category = trim($request->getParam('category', false));
		$skin = trim($request->getParam('skin', -1));
		$dateFilter = array(trim($request->getParam('dateFrom', false)), trim($request->getParam('dateTo', false)));
		
		$VipShopPurchases = new Application_Model_VipShopPurchases();

		$VipShopPurchases->addGlobalSearchFilters($filter);
		$VipShopPurchases->addUserroleFilter($level);
		$VipShopPurchases->addCategoryFilter($category);
		$VipShopPurchases->addSkinFilter($skin);
		$VipShopPurchases->addDateFilters($dateFilter[0], $dateFilter[1]);
		
		$pagination_limit = Zend_Registry::get('config')->pagination_limit;

		// prepare pagination
		$this->view->pagination_current_page = $page;
		$this->view->pagination_last_page = (int)ceil($VipShopPurchases->getAllSales(true) / (int)$pagination_limit);

		// set order
		$order_by = empty($sort['sortBy']) ? 'id' : $sort['sortBy'];
		$order_sort = empty($sort['order']) ? 'DESC' : $sort['order'];

		if ($order_by) {
			$VipShopPurchases->order_by = $order_by;
			$VipShopPurchases->order_sort = $order_sort;
		}

		// export to csv?
		if ($request->getParam('doexport')){
			$all_data = $VipShopPurchases->getAllSales(false, true, true);
			Application_Plugin_Common::exportToCsv($all_data, $request->getParam('doexport'));
		}
		
		// get single page data
		$VipShopPurchases->page_number = $page;
		$data = $VipShopPurchases->getAllSales();

		$this->view->data = $data;

		// TODO get template from ajax
		$table = $this->view->render('/partial/salesreporttable.phtml');

		echo $this->_helper->json(array("table" => $table));
	}
	
	
	/**
	 *
	 * rendering table
	 */
	public function rendervideosalesreporttableAction()
	{
		$request = $this->getRequest();
		$sort = $request->getParam('sort', array());
	
		$page = empty($sort['page']) ? 1 : $sort['page'];
	
		$filter = trim($request->getParam('filter', false));
		$level = trim($request->getParam('level', false));
		$category = trim($request->getParam('category', false));
		$skin = trim($request->getParam('skin', -1));
		$dateFilter = array(trim($request->getParam('dateFrom', false)), trim($request->getParam('dateTo', false)));
	
		$VipShopPurchases = new Application_Model_VipShopPurchases();
	
		// important: filter only videos
		$VipShopPurchases->where_filters = " AND pokerbo_vipshop_purchases.product_category = 'video' ";
		
		$VipShopPurchases->addGlobalSearchFilters($filter);
		$VipShopPurchases->addUserroleFilter($level);
		$VipShopPurchases->addDateFilters($dateFilter[0], $dateFilter[1]);
	
		$pagination_limit = Zend_Registry::get('config')->pagination_limit;
	
		// prepare pagination
		$this->view->pagination_current_page = $page;
		$this->view->pagination_last_page = (int)ceil($VipShopPurchases->getAllSales(true) / (int)$pagination_limit);
	
		// set order
		$order_by = empty($sort['sortBy']) ? 'id' : $sort['sortBy'];
		$order_sort = empty($sort['order']) ? 'DESC' : $sort['order'];
	
		if ($order_by) {
			$VipShopPurchases->order_by = $order_by;
			$VipShopPurchases->order_sort = $order_sort;
		}
	
		// export to csv?
		if ($request->getParam('doexport')){
			$all_data = $VipShopPurchases->getAllSales(false, true, true);
			Application_Plugin_Common::exportToCsv($all_data, $request->getParam('doexport'));
		}
	
		// get single page data
		$VipShopPurchases->page_number = $page;
		$data = $VipShopPurchases->getAllSales();
	
		$this->view->data = $data;
	
		// TODO get template from ajax
		$table = $this->view->render('/partial/videosalesreporttable.phtml');
	
		echo $this->_helper->json(array("table" => $table));
	}
	

	/**
	 *
	 * rendering table
	 */
	public function renderproductstableAction()
	{
		$request = $this->getRequest();
		$sort = $request->getParam('sort', array());
	
		$page = empty($sort['page']) ? 1 : $sort['page'];
	
		$filter = trim($request->getParam('filter', false));
		$level = trim($request->getParam('level', -1));
		$category = trim($request->getParam('category', false));
		$skin = trim($request->getParam('skin', -1));
		$user_role = trim($request->getParam('user_role', -1));
		$published = trim($request->getParam('published', -1));
	
		$VipShopProducts = new Application_Model_VipShopProducts();
	
		$VipShopProducts->addGlobalSearchFilters($filter);
		$VipShopProducts->addCategoryFilter($category);
		$VipShopProducts->addSkinFilter($skin);
		$VipShopProducts->addUserRoleFilter($user_role);
		$VipShopProducts->addPublishedFilter($published);

		$pagination_limit = Zend_Registry::get('config')->pagination_limit;
	
		// prepare pagination
		$this->view->pagination_current_page = $page;
		$this->view->pagination_last_page = (int)ceil($VipShopProducts->getAllProducts(true) / (int)$pagination_limit);
	
		// set order
		$order_by = empty($sort['sortBy']) ? '' : $sort['sortBy'];
		$order_sort = empty($sort['order']) ? '' : $sort['order'];
	
		if ($order_by) {
			$VipShopProducts->order_by = $order_by;
			$VipShopProducts->order_sort = $order_sort;
		}
	
		// export to csv?
		if ($request->getParam('doexport')){
			$all_data = $VipShopProducts->getAllProducts(false, true, true);
			Application_Plugin_Common::exportToCsv($all_data, $request->getParam('doexport'));
		}
	
		// get single page data
		$VipShopProducts->page_number = $page;
		$data = $VipShopProducts->getAllProducts();
	
		$this->view->data = $data;
	
		// TODO get template from ajax
		$table = $this->view->render('/partial/productstable.phtml');
	
		echo $this->_helper->json(array("table" => $table));
	}

	

	/**
	 *
	 * rendering table
	 */
	public function rendervideostableAction()
	{
		$request = $this->getRequest();
		$sort = $request->getParam('sort', array());
	
		$page = empty($sort['page']) ? 1 : $sort['page'];
	
		$filter = trim($request->getParam('filter', false));
		$level = trim($request->getParam('level', -1));
		$category = trim($request->getParam('category', false));
		$skin = trim($request->getParam('skin', -1));
		$user_role = trim($request->getParam('user_role', -1));
		$published = trim($request->getParam('published', -1));
	
		$VipShopProducts = new Application_Model_VipShopProducts();
		
		// important: filter only videos
		$VipShopProducts->where_filters = " AND (pokerbo_vipshop_products.product_category = 'video'  OR pokerbo_vipshop_products.product_category = 'livestream') ";
		
		$VipShopProducts->addGlobalSearchFilters($filter);
		$VipShopProducts->addCategoryFilter($category);
		$VipShopProducts->addSkinFilter($skin);
		$VipShopProducts->addUserRoleFilter($user_role);
		$VipShopProducts->addPublishedFilter($published);
	
		$pagination_limit = Zend_Registry::get('config')->pagination_limit;
	
		// prepare pagination
		$this->view->pagination_current_page = $page;
		$this->view->pagination_last_page = (int)ceil($VipShopProducts->getAllProducts(true) / (int)$pagination_limit);
	
		// set order
		$order_by = empty($sort['sortBy']) ? '' : $sort['sortBy'];
		$order_sort = empty($sort['order']) ? '' : $sort['order'];
	
		if ($order_by) {
			$VipShopProducts->order_by = $order_by;
			$VipShopProducts->order_sort = $order_sort;
		}
	
		// export to csv?
		if ($request->getParam('doexport')){
			$all_data = $VipShopProducts->getAllProducts(false, true, true);
			Application_Plugin_Common::exportToCsv($all_data, $request->getParam('doexport'));
		}
	
		// get single page data
		$VipShopProducts->page_number = $page;
		$data = $VipShopProducts->getAllProducts();
	
		$this->view->data = $data;
	
		// TODO get template from ajax
		$table = $this->view->render('/partial/videostable.phtml');
	
		echo $this->_helper->json(array("table" => $table));
	}
	
	public function renderclientpromotionstableAction()
	{
		$Images = new Application_Model_Images();
		
		$promotions = $Images->getPromotions();
		
		$this->view->promotions = $promotions;
		
		$table = $this->view->render('/partial/clientpromotionstable.phtml');
		echo $this->_helper->json(array("table" => $table));
	}
	
	public function editpromotionAction()
	{
		$request = $this->getRequest();
		
		$Images = new Application_Model_Images();
		
		$Images->editProm($request->promotionid, $request->promotionname, $request->active);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data" => "Promotion {$request->promotionname} successufly updated!",
			)
		);
		return;
	}
	
	public function createnewpromotionAction()
	{
		$request = $this->getRequest();
		$name = $request->getParam('promotionname', array());
		$active = $request->getParam('active', 0);
		
		$Images = new Application_Model_Images();
		
		$nameExists = $Images->checkPromName($name);
		
		if ($nameExists) {
			echo $this->_helper->json(
				array(
					"status" => 1,
					"error"  => "Promotion name already exists!",
				)
			);
			return;
		}
		
		$res = $Images->insertNewProm($name, $active);
		
		if($res){
			echo $this->_helper->json(
				array(
					"status" => 0,
					"data"  => "Promotion '{$name}' created!",
				)
			);
			return;
		} else {
			echo $this->_helper->json(
				array(
					"status" => 1,
					"error"  => "Error inserting new promotion!",
				)
			);
			return;
		}
	}
	
	public function createnewpromotionlangAction()
	{
		$request = $this->getRequest();
		
		$Images = new Application_Model_Images();
		
		$res = $Images->insertUpdatePromLang($request);
		
		if($res){
			echo $this->_helper->json(
				array(
					"status" => 0,
					"data"  => $res,
				)
			);
			return;
		} else {
			echo $this->_helper->json(
				array(
					"status" => 1,
					"error"  => "Error inserting new promotion language!",
				)
			);
			return;
		}
	}
	
	public function getpromlangsdataAction()
	{
		$request = $this->getRequest();
		
		$Images = new Application_Model_Images();
		
		$res = $Images->getLangProm($request->promid);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => $res,
			)
		);
		return;
	}
	
	public function getskinsAction()
	{
		$request = $this->getRequest();
		
		$HiveCmsSkins = new Application_Model_HiveCmsSkins();
		
		$skins = $HiveCmsSkins->getSkin($request->partnerid);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => $skins,
			)
		);
		return;
	}
	
	public function getuserdataAction()
	{
		$request = $this->getRequest();
		
		$HiveCmsUsers = new Application_Model_HiveCmsUsers();
		
		$user = $HiveCmsUsers->getUserById($request->userid);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => $user,
			)
		);
		return;
	}
	
	public function renderhivecmsskinstableAction()
	{
		$HiveCmsPartners = new Application_Model_HiveCmsPartners();
		$HiveCmsSkins = new Application_Model_HiveCmsSkins();
		$HiveCmsLanguages = new Application_Model_HiveCmsLanguages();
		$HiveCmsSkinsLanguages = new Application_Model_HiveCmsSkinsLanguages();
		
		$this->view->langs = $HiveCmsLanguages->getLanguages();
		
		$this->view->partners = $HiveCmsPartners->getPartners();
		
		$this->view->skins = $HiveCmsSkins->getSkins();
		
		if ($this->view->skins) {
			foreach ($this->view->skins as $k=>$row) {
				$langs = $HiveCmsSkinsLanguages->getSkinLangs($row["skinid"]);
				$this->view->skins[$k]["langs"] = "";
				if ($langs) {
					foreach ($langs as $key=>$value) {
						$this->view->skins[$k]["langs"] .= $value["short_lang"];
						$this->view->skins[$k]["langs"] .= $key < count($langs) - 1 ? ", " : "";
					}
				}
			}
		}
		
		$table = $this->view->render('/partial/hivecmsskinstable.phtml');
		echo $this->_helper->json(array("table" => $table));
	}
	
	public function renderhivecmspartnerstableAction()
	{
		$HiveCmsPartners = new Application_Model_HiveCmsPartners();
		
		$this->view->partners = $HiveCmsPartners->getPartners();
		
		$table = $this->view->render('/partial/hivecmspartnerstable.phtml');
		echo $this->_helper->json(array("table" => $table));
	}
	
	public function addnewpartnerAction()
	{
		$request = $this->getRequest();
		
		$HiveCmsPartners = new Application_Model_HiveCmsPartners();
		
		$skins = $HiveCmsPartners->savePartner($request);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => "Partner '{$request->partnername}' sucessufly added!",
			)
		);
		return;
	}
	
	public function addnewskinAction()
	{
		$request = $this->getRequest();

		$HiveCmsSkins = new Application_Model_HiveCmsSkins();
		$HiveCmsSkinsLanguages = new Application_Model_HiveCmsSkinsLanguages();
		
		$skin = $HiveCmsSkins->saveSkin($request);
		
		if($skin) {
			$skinLangs = $HiveCmsSkinsLanguages->saveSkinLanguage($request);
		
			echo $this->_helper->json(
				array(
					"status" => 0,
					"data"  => "Skin '{$request->name}' sucessufly added!",
				)
			);
			return;
		} else {
			echo $this->_helper->json(
				array(
					"status" => 1,
					"error"  => "Error inserting skin!",
				)
			);
			return;
		}
	}
	
	public function editskinAction()
	{
		$request = $this->getRequest();

		$HiveCmsSkins = new Application_Model_HiveCmsSkins();
		$HiveCmsSkinsLanguages = new Application_Model_HiveCmsSkinsLanguages();
		
		$skin = $HiveCmsSkins->editSkin($request);
		
		$HiveCmsSkinsLanguages->deleteSkinLanguage($request);
		$skinLangs = $HiveCmsSkinsLanguages->saveSkinLanguage($request);
	
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => "Skin '{$request->name}' sucessufly edited!",
			)
		);
		return;
	}
	
	public function getskinlangsAction()
	{
		$request = $this->getRequest();
		
		$HiveCmsSkinsLanguages = new Application_Model_HiveCmsSkinsLanguages();
		
		$skins = $HiveCmsSkinsLanguages->getSkinLangs($request->skinid);
		$skinids = array();
		if ($skins) {
			foreach($skins as $k=>$row) {
				array_push($skinids, (int)$row["langid"]);
			}
		}
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => $skinids,
			)
		);
		return;
	}
	
	public function renderhivecmsuserstableAction()
	{
		$HiveCmsUsers = new Application_Model_HiveCmsUsers();
		
		$this->view->data = $HiveCmsUsers->getUsers();
		
		$table = $this->view->render('/partial/hivecmsuserstable.phtml');
		echo $this->_helper->json(array("table" => $table));
	}
	
	public function addnewuserAction()
	{
		$request = $this->getRequest();
		
		$HiveCmsUsers = new Application_Model_HiveCmsUsers();
		
		$users = $HiveCmsUsers->saveUser($request);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => "User '{$request->username}' sucessufly added!",
			)
		);
		return;
	}
	
	public function renderhivecmsrolestableAction()
	{
		$HiveCmsRoles = new Application_Model_HiveCmsRoles();
		
		$this->view->roles = $HiveCmsRoles->getRoles();
		
		$table = $this->view->render('/partial/hivecmsrolestable.phtml');
		echo $this->_helper->json(array("table" => $table));
	}
	
	public function addnewroleAction()
	{
		$request = $this->getRequest();
		
		$HiveCmsRoles = new Application_Model_HiveCmsRoles();
		
		$roles = $HiveCmsRoles->saveRole($request);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => "Role '{$request->role}' sucessufly added!",
			)
		);
		return;
	}
	
	public function renderhivecmslanguagestableAction()
	{
		$HiveCmsLanguages = new Application_Model_HiveCmsLanguages();
		
		$this->view->langs = $HiveCmsLanguages->getLanguages();
		
		$table = $this->view->render('/partial/hivecmslanguagestable.phtml');
		echo $this->_helper->json(array("table" => $table));
	}
	
	public function addnewlanguageAction()
	{
		$request = $this->getRequest();
		
		$HiveCmsLanguages = new Application_Model_HiveCmsLanguages();
		
		$langs= $HiveCmsLanguages->saveLanguage($request);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => "Language '{$request->lang}' sucessufly added!",
			)
		);
		return;
	}
	
	public function sortpromotionsAction()
	{
		$request = $this->getRequest();
		
		$Images = new Application_Model_Images();
		
		$Images->sortProms($request->data);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => "Promotions successufuly sorted!",
			)
		);
		return;
	}
	
	public function edituserAction()
	{
		$request = $this->getRequest();

		$HiveCmsUsers = new Application_Model_HiveCmsUsers();
		
		$users = $HiveCmsUsers->saveUser($request);
		
		echo $this->_helper->json(
			array(
				"status" => 0,
				"data"  => "User '{$request->username}' sucessufly edited!",
			)
		);
		return;
	}
	
}