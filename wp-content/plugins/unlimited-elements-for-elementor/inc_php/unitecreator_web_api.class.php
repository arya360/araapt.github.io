<?php

/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorWebAPIWork{
	
	private static $urlAPI;
	private static $arrCatalogData;
	protected $product;
	
	const CATALOG_CHECK_PERIOD = 7200;	 		//2 hours
	const CATALOG_CHECK_PERIOD_NOTEXIST = 600;	//10 min
	const OPTION_ACTIVATION_BASE = "addon_library_activation";
	
	protected $optionActivation = self::OPTION_ACTIVATION_BASE;
	
	const OPTION_CATALOG = "addon_library_catalog";
	const OPTION_TIMEOUT_TRANSIENT = "addon_library_catalog_timeout";
	
	const EXPIRE_NEVER = "never";

	
	private function a_INIT(){}
	
	
	/**
	 * construct
	 */
	public function __construct(){
		
		if(empty(self::$urlAPI))
			self::$urlAPI = GlobalsUC::URL_API;
	}
	
	
	/**
	 * set product
	 */
	public function setProduct($product){
		
		UniteFunctionsUC::validateNotEmpty($product, "product");
		
		$this->product = $product;
		
		$this->optionActivation = self::OPTION_ACTIVATION_BASE."_".$product;
				
	}
	
	
	private function a__________GETTERS___________(){}

	
	
	/**
	 * get activated product data
	 */
	private function getActivatedData(){
		
		$arrActivation = UniteProviderFunctionsUC::getOption($this->optionActivation);
		if(empty($arrActivation))
			return(null);
	
		return($arrActivation);
	}
	
	
	/**
	 * get activation code
	 */
	private function getActivationCode(){
	
		$arrActivation = UniteProviderFunctionsUC::getOption($this->optionActivation);
		if(empty($arrActivation))
			return("");
	
		$code = UniteFunctionsUC::getVal($arrActivation, "code");
	
		return($code);
	}
	
	
	/**
	 * get addon names array
	 */
	private function getArrAddonNames($arrCatalogAddons){
		
		if(empty($arrCatalogAddons))
			return(array());
		
		$arrNames = array();
		foreach($arrCatalogAddons as $catName => $arrCat){
			
			foreach($arrCat as $addon){
							
				$name = UniteFunctionsUC::getVal($addon, "name");
				
				unset($addon["name"]);
				
				$addon["cat"] = $catName;
				
				$arrNames[$name] = $addon;
			}
		}
		
		
		return($arrNames);
	}
	
	
	/**
	 * modify data before save
	 */
	private function modifyArrData($arrData){
				
		//add addon names 
		
		$arrData["catalog_addon_names"] = array();
		
		$arrCatalog = UniteFunctionsUC::getVal($arrData, "catalog");
		$arrAddons = UniteFunctionsUC::getVal($arrCatalog, "addons");
		
		$addonNames = $this->getArrAddonNames($arrAddons);
		
		$arrData["catalog_addon_names"] = $addonNames;
		
		
		return($arrData);
	}
	
	
	/**
	 * get catalog data
	 */
	private function getCatalogData(){
		
		if(!empty(self::$arrCatalogData))
			return(self::$arrCatalogData);
		
		$arrData = UniteProviderFunctionsUC::getOption(self::OPTION_CATALOG);
		
		if(is_array($arrData) == false)
			return(null);
			
		$arrData = $this->modifyArrData($arrData);
				
		self::$arrCatalogData = $arrData;
	
		return($arrData);
	}
	
	
	/**
	 * get full catalog array
	 * Enter description here ...
	 */
	private function getCatalogArrayFromData(){
		
		$arrData = $this->getCatalogData();
		if(empty($arrData))
			return(array());
		
		$arrCatalog = UniteFunctionsUC::getVal($arrData, "catalog");
				
		//return from old way
		if(!isset($arrCatalog["addons"])){
			$arrCatalogOutput = array();
			$arrCatalogOutput["addons"] = $arrCatalog;
			$arrCatalogOutput["pages"] = array();
			
			return($arrCatalogOutput);
		}
		
		
		return($arrCatalog);
	}
	
	
	/**
	 * get catalog array
	 */
	protected function getCatalogArray_addons(){
		
		$arrCatalog = $this->getCatalogArrayFromData();
		
		$arrCatalogAddons = $arrCatalog["addons"];
		
		return($arrCatalogAddons);		
	}

	
	/**
	 * get catalog array
	 */
	public function getCatalogArray_pages(){
		
		$arrCatalog = $this->getCatalogArrayFromData();
		
		$arrCatalogAddons = $arrCatalog["pages"];
		
		return($arrCatalogAddons);		
	}
	
	/**
	 * filter web categories by addon type
	 */
	private function filterWebCategoriesByAddonType($arrCatalogItems, $objAddonsType){
		
		if(empty($arrCatalogItems))
			return($arrCatalogItems);
		
		$arrExclude = $objAddonsType->arrCatalogExcludeCats;
		if(empty($arrExclude))
			return($arrCatalogItems);
		
		if(is_array($arrExclude) == false)
			return($arrCatalogItems);
			
		foreach($arrCatalogItems as $catName => $arrAddons){
			
			$nameLower = strtolower($catName);
			
			if(in_array($nameLower, $arrExclude) === false)
				continue;
			
			unset($arrCatalogItems[$catName]);
		}
			
		return($arrCatalogItems);
	}
	
	
	/**
	 * get catalog array by addons type
	 */
	public function getCatalogArray($objAddonsType){
		
		$key = $objAddonsType->catalogKey;
		$arrCatalog = $this->getCatalogArrayFromData();
		
		$arrCatalogItems = UniteFunctionsUC::getVal($arrCatalog, $key);
		if(empty($arrCatalogItems))
			$arrCatalogItems = array();
		
		$arrCatalogItems = $this->filterWebCategoriesByAddonType($arrCatalogItems, $objAddonsType);
					
		return($arrCatalogItems);
	}
	
	
	
	/**
	 * print catalog
	 */
	public function printCatalog(){
		
		$arrCatalog = $this->getCatalogArrayFromData();
		
		dmp($arrCatalog);
		exit();
	}
	
	
	/**
	 * get catalog addon names
	 */
	private function getArrCatalogAddonNames(){
		
		$arrData = $this->getCatalogData();
		
		if(empty($arrData))
			return(array());
					
		$arrNames = UniteFunctionsUC::getVal($arrData, "catalog_addon_names");
				
		return($arrNames);
	}
	
	
	/**
	 * check if product active or not
	 */
	public function isProductActive($product = null){
		
				
		if(!empty($product)){
			$this->setProduct($product);
		}		
		
		$data = $this->getActivatedData();
				
		if(empty($data))
			return(false);
		
		$stampExpire = UniteFunctionsUC::getVal($data, "expire");
		
		if($stampExpire === self::EXPIRE_NEVER)
			return(true);
		
		if(empty($stampExpire))
			return(false);
	
		if(is_numeric($stampExpire) == false)
			return(false);
		
		$stampExpire = (int)$stampExpire;
		$stampNow = time();
	
		if($stampExpire < $stampNow)
			return(false);
	
		return(true);
	}
	
	
	/**
	 * check if time to check catalog
	 */
	public function isTimeToCheckCatalog(){
	
		$timeout = UniteProviderFunctionsUC::getTransient(self::OPTION_TIMEOUT_TRANSIENT);
	
		if(empty($timeout))
			return(true);
		else
			return(false);
	}
	
	
		
	
	/**
	 * get catalog version
	 */
	public function getCurrentCatalogStamp(){
	
		$arrData = $this->getCatalogData();
		if(empty($arrData))
			return(null);
	
		$stamp = UniteFunctionsUC::getVal($arrData, "stamp");
	
		return($stamp);
	}
	
	
	/**
	 * get current catalog date
	 */
	public function getCurrentCatalogDate(){
	
		$isExists = $this->isCatalogExists();
		if($isExists == false)
			return("");
	
		$stamp = $this->getCurrentCatalogStamp();
	
		if(empty($stamp))
			return("");
	
		$date = UniteFunctionsUC::timestamp2Date($stamp);
	
		return($date);
	}
	
	/**
	 * check if the saved catalog exists
	 */
	public function isCatalogExists(){
		
		$arrData = $this->getCatalogData();
		
		if(empty($arrData))
			return(false);
	
		return(true);
	}
	
	/**
	 * is pages catalog exists
	 */
	public function isPagesCatalogExists(){
		
		if($this->isCatalogExists() == false)
			return(false);
		
		$arrPages = $this->getCatalogArray_pages();
		if(empty($arrPages))
			return(false);
		
		return(true);
	}
	
	/**
	 * check if addon exists in catalog
	 * if empty catalog return false
	 */
	public function isAddonExistsInCatalog($addonName){
								
		$arrNames = $this->getArrCatalogAddonNames();
		
		if(isset($arrNames[$addonName]))
			return(true);
		
		return(false);
	}
	
	/**
	 * get simple addon by name
	 */
	public function getAddonByName($name){
		
		$arrNames = $this->getArrCatalogAddonNames();
		
		$arrAddon = UniteFunctionsUC::getVal($arrNames, $name);
		
		if(empty($arrAddon))
			return(null);
			
		$arrAddon["name"] = $name;
		
		return($arrAddon);
	}
	
	
	
	private function a___________SETTERS___________(){}
	
	/**
	 * modify data before request
	 */
	protected function modifyDataBeforeRequest($data){
		
		return($data);
	}
	
	/**
	 * call API with some action and data
	 */
	private function callAPI($action, $data = array(), $isRawResponse = false){
				
		$data["action"] = $action;
		$data["domain"] = GlobalsUC::$current_host;
		
		if(!isset($data["code"]))
			$data["code"] = $this->getActivationCode();
		
		if(!isset($data["catalog_date"]))
			$data["catalog_date"] = $this->getCurrentCatalogStamp();
		
		$data["blox_version"] = UNLIMITED_ELEMENTS_VERSION;
		
		$data = $this->modifyDataBeforeRequest($data);
		
		
		$response = UniteFunctionsUC::getUrlContents(self::$urlAPI, $data);
		
		if($isRawResponse == true){
			$len = strlen($response);
			if($len < 200){
				$objResponse = @json_decode($response);
				if(empty($objResponse))
					return($objResponse);
			}else
				return($response);
		}
		
		if(empty($response))
			UniteFunctionsUC::throwError("Wrong API Response");
		
		$arrResponse = UniteFunctionsUC::jsonDecode($response);
		
		if(empty($arrResponse))
			UniteFunctionsUC::throwError("wrong API response: ".$response);
		
		$success = UniteFunctionsUC::getVal($arrResponse, "success");
		$success = UniteFunctionsUC::strToBool($success);
		if($success == false){
			$message = UniteFunctionsUC::getVal($arrResponse,"message");
			if(empty($message))
				$message = "There was some error";
			
			$message = "server error: ".$message;
			
			UniteFunctionsUC::throwError($message);
		}
		
		return($arrResponse);
	}
	
	
	
	/**
	 * save activated product
	 * save purchase code and expire days
	 */
	private function saveActivatedProduct($code, $expireStamp){
		
		$arrActivation = array();
		$arrActivation["code"] = $code;
				
		
		if(empty($expireStamp))
			$arrActivation["expire"] = self::EXPIRE_NEVER;
		else
			$arrActivation["expire"] = $expireStamp;
		
		
		UniteProviderFunctionsUC::updateOption($this->optionActivation, $arrActivation);
	}
	
	
	/**
	 * delete saved catalog
	 */
	public function deleteCatalog(){
	
		UniteProviderFunctionsUC::deleteOption(self::OPTION_CATALOG);
	
	}
	
	
	/**
	 * deactivate product
	 */
	public function deactivateProduct($data = null){
		
		$product = UniteFunctionsUC::getVal($data, "product");
		
		if(!empty($product))
			$this->setProduct($product);
			
		
		UniteProviderFunctionsUC::deleteOption($this->optionActivation);
		
	}
	
	
	/**
	 * activate product from data
	 */
	public function activateProductFromData($data){
				
		$code = UniteFunctionsUC::getVal($data, "code");
		$codetype = UniteFunctionsUC::getVal($data, "codetype");
		$product = UniteFunctionsUC::getVal($data, "product");
		
		if(!empty($product))
			$this->setProduct($product);
		
		UniteFunctionsUC::validateNotEmpty($code, "Activation Code");
		UniteFunctionsUC::validateNotEmpty($codetype, "Code Type");
		
		$reqData = array();
		$reqData["code"] = $code;
		$reqData["codetype"] = $codetype;
		
		if(!empty($product))
			$reqData["product"] = $product;
		
		$responseAPI = $this->callAPI("activate", $reqData);
		
		
		// ------------- 
		
		$expireStamp = UniteFunctionsUC::getVal($responseAPI, "expire_stamp");
		$expireDays = UniteFunctionsUC::getVal($responseAPI, "expire_days");
		
		//save activation
		$this->saveActivatedProduct($code, $expireStamp);
		
		
		return($expireDays);
	}
	
		
	
	/**
	 * save catalog data
	 */
	private function saveCatalogData($stamp, $arrCatalog){
		
		$arrData = array();
		$arrData["stamp"] = $stamp;
		$arrData["catalog"] = $arrCatalog;
		$arrData["catalog_addon_names"] = $this->getArrAddonNames($arrCatalog);
		
		
		UniteProviderFunctionsUC::updateOption(self::OPTION_CATALOG, $arrData);
		
	}
	
	
	/**
	 * check or update catalog in web
	 */
	public function checkUpdateCatalog(){
			
		try{
			
			$isCatalogExists = $this->isCatalogExists();
			
			if($isCatalogExists == false){
				$checkPerioud = self::CATALOG_CHECK_PERIOD_NOTEXIST;
				$catalogStamp = null;
			}else{
				
				//update transient, for wait perioud
				$checkPerioud = self::CATALOG_CHECK_PERIOD;				
				
				$catalogStamp = $this->getCurrentCatalogStamp();
				
				if(empty($catalogStamp))
					$checkPerioud = self::CATALOG_CHECK_PERIOD_NOTEXIST;
				
			}
			
			UniteProviderFunctionsUC::setTransient(self::OPTION_TIMEOUT_TRANSIENT, true, $checkPerioud);
			
			
			$data = array();
			$data["catalog_date"] = $catalogStamp;
			$data["include_pages"] = true;
			
			$response = $this->callAPI("check_catalog", $data);
			
			
			$updateFound = UniteFunctionsUC::getVal($response, "update_found");
			$updateFound = UniteFunctionsUC::strToBool($updateFound);
			
			$clientResponse = array();
			
			//response up to date
			if($updateFound == false){
				$clientResponse["update_found"] = false;
				$catalogDate = UniteFunctionsUC::timestamp2DateTime($catalogStamp);
				$clientResponse["message"] = "The catalog is up to date: ".$catalogDate;
				
				return($clientResponse);
			}
			
			$stamp = UniteFunctionsUC::getVal($response, "stamp");
			$arrCatalog = UniteFunctionsUC::getVal($response, "catalog");
			
			$this->saveCatalogData($stamp, $arrCatalog);
			
			//response catalog date
			$date = UniteFunctionsUC::timestamp2DateTime($stamp);
			$clientResponse["update_found"] = true;
			$clientResponse["catalog_date"] = $date;
			$clientResponse["message"] = "The catalog updated. Catalog Date: $date. <br><br> Please refresh the browser to see the changes";
			
			return($clientResponse);
			
		}catch(Exception $e){
			
			$message = $e->getMessage();
			
			$clientResponse = array();
			$clientResponse["update_found"] = false;
			$clientResponse["error_message"] = $message;
			
			return($clientResponse);			
		}
		
	}
	
	/**
	 * check if supported addon type
	 */
	protected function isAddonTypeSupported($objAddonsType){
				
		$isSupported = $objAddonsType->allowWebCatalog;
		
		return($isSupported);
	}
	
	
	/**
	 * merge addons with catalog from all the categories
	 */
	public function mergeAddonsWithCatalog($arrAddons, $objAddonsType){
		
		if($this->isAddonTypeSupported($objAddonsType) == false)
			return($arrAddons);
		
		$arrAssoc = UniteFunctionsUC::arrayToAssoc($arrAddons,"name");
		
		$arrWebCatalog = $this->getCatalogArray($objAddonsType);
		
		if(empty($arrWebCatalog))
			return($arrAddons);
		
		$addonType = $objAddonsType->typeName;
		if($objAddonsType->isBasicType == true)
			$addonType = "";
		
			
		foreach($arrWebCatalog as $cat=>$catAddons){
			
			foreach($catAddons as $arrAddon){
				$name = UniteFunctionsUC::getVal($arrAddon, "name");
				
				$name2 = null;
				if(!empty($addonType))
					$name2 = $name."_".$addonType;
									
				if(isset($arrAssoc[$name]))
					continue;
				
				if(!empty($name2) && isset($arrAssoc[$name]))
					continue;
				
					
				$arrAddon["isweb"] = true;
				$arrAddon["cat"] = $cat;
				$arrAddons[] = $arrAddon;
			}
		}
		
		
		return($arrAddons);
	}
	
	
	/**
	 * merge categories and layouts
	 */
	public function mergeCatsAndLayoutsWithCatalog($arrCats, $objAddonsType){
		
		if($this->isAddonTypeSupported($objAddonsType) == false)
			return($arrCats);
		
		if($this->isCatalogExists() == false)
			$this->checkUpdateCatalog();
		
		$arrWebCatalog = $this->getCatalogArray($objAddonsType);
				
		if(empty($arrWebCatalog))
			return($arrCats);
		
		foreach($arrWebCatalog as $cat=>$arrLayouts){
			
			if(!isset($arrCats[$cat]))
				$arrCats[$cat] = array();
			
			foreach($arrLayouts as $name=>$layout){
				
				if(isset($arrCats[$cat][$name]))
					continue;
				
				$layout["isweb"] = true;
				$arrCats[$cat][$name] = $layout;
			}
			
		}
		
		return($arrCats);
	}
	
	/**
	 * filter catalog by type
	 */
	private function filterCatalogBySearchString($arrWebCatalog, $strSearch){
		
		foreach($arrWebCatalog as $catTitle => $arrAddons){
			
			//filter by category, leave all the category if contains
			$isMatch = UniteFunctionsUC::isStringContains($catTitle, $strSearch);
						
			if($isMatch == true)
				continue;
			
			$arrAddonsNew = $this->filterCatalogAddonsBySearchString($arrAddons, $strSearch);
			
			//build only match array
			if(empty($arrAddonsNew)){
				unset($arrWebCatalog[$catTitle]);				
				continue;
			}
			
			
			$arrWebCatalog[$catTitle] = $arrAddonsNew;
		}

		
		return($arrWebCatalog);
	}
	
	/**
	 * filter addons by search string
	 */
	private function filterCatalogAddonsBySearchString($arrAddons, $strSearch){
		
		if(empty($strSearch))
			return($arrAddons);
		
		if(empty($arrAddons))
			return(array());
			
		$arrAddonsNew = array();
		foreach($arrAddons as $addon){
			$titleAddon = UniteFunctionsUC::getVal($addon, "title");
			
			$isAddonMatch = UniteFunctionsUC::isStringContains($titleAddon, $strSearch);
			
			if($isAddonMatch == false)
				continue;
			
			$arrAddonsNew[] = $addon;
		}
		
		return($arrAddonsNew);
		
	}
	
	/**
	 * merge cats with catalog cats
	 */
	public function mergeCatsAndAddonsWithCatalog($arrCats, $numAddonsOnly = false, $objAddonsType, $params = null){
		
		if($this->isAddonTypeSupported($objAddonsType) == false)
			return($arrCats);
		
		if($this->isCatalogExists() == false)
			$this->checkUpdateCatalog();
		
		$arrWebCatalog = $this->getCatalogArray($objAddonsType);
		
		$filterSearch = UniteFunctionsUC::getVal($params, "filter_search");
		$filterSearch = trim($filterSearch);
		
		
		if(!empty($filterSearch))
			$arrWebCatalog = $this->filterCatalogBySearchString($arrWebCatalog, $filterSearch);
					
		
		if(empty($arrWebCatalog))
			return($arrCats);
		
		$addonType = $objAddonsType->typeName;
		if($objAddonsType->isBasicType == true)
			$addonType = "";
		
		
		foreach($arrWebCatalog as $dir=>$addons){
						
			//add directory
			if(isset($arrCats[$dir]) == false){
								
				$catHandle = HelperUC::convertTitleToHandle($dir);
				$catID = "ucweb_".$catHandle;
				
				$arrCats[$dir] = array(
					"id"=>$catID,
					"isweb"=>true,
					"title"=>$dir,
					"addons"=>array()
				);
				
			}
			
			$numWebAddons = 0;
			
			//add addons from web to existing folder
			foreach($addons as $addonName => $arrAddon){
				
				$name = UniteFunctionsUC::getVal($arrAddon, "name");
				if(empty($name))
					$name = $addonName;

				$name2 = null;
				if(!empty($addonType))
					$name2 = $name."_".$addonType;
					
				
				//search for the addon in cats
				if(isset($arrCats[$dir]["addons"][$name])){
					continue;
				}
				
				
				if(!empty($name2) && isset($arrCats[$dir]["addons"][$name2]))
					continue;
				
				
				//addo not found, add the web addon
				$arrAddon["isweb"] = true;
				$arrCats[$dir]["addons"][$name] = $arrAddon;
				$numWebAddons++;
				
			}
			
			$arrCats[$dir]["num_web_addons"] = $numWebAddons;
		}
		
		
		if($numAddonsOnly == false)
			return($arrCats);
		
		//replace the addons bu num addons
		foreach($arrCats as $dir=>$cat){
			
			$arrAddons = UniteFunctionsUC::getVal($cat, "addons");
			$numAddons = 0;
			if(!empty($arrAddons))
				$numAddons = count($arrAddons);
			
			$arrCats[$dir]["num_addons"] = $numAddons;
			unset($arrCats[$dir]["addons"]);
			
			//delete uncategorized if empty
			$catID = UniteFunctionsUC::getVal($cat, "id");
			if($catID == 0 && $numAddons == 0)
				unset($arrCats[$dir]);
			
		}
	
		//dmp($arrCats);exit();
		
		return($arrCats);
	}
	
	/**
	 * merge addons objects with the addons from catalog
	 */
	public function mergeCatAddonsWithCatalog($title, $arrAddons, $objAddonsType, $params = null){
		
		//don't work with another addon types
		if($this->isAddonTypeSupported($objAddonsType) == false)
			return($arrAddons);
		
		$arrCatalogAddons = $this->getArrCatAddons($title, $objAddonsType);
		
		$filterSearch = UniteFunctionsUC::getVal($params, "filter_search");
		$filterSearch = trim($filterSearch);
		
		if(!empty($filterSearch))
			$arrCatalogAddons = $this->filterCatalogAddonsBySearchString($arrCatalogAddons, $filterSearch);
			
		
		if(empty($arrCatalogAddons))
			return($arrAddons);
		
		$addonType = $objAddonsType->typeName;
		if($objAddonsType->isBasicType == true)
			$addonType = "";
				
		$arrNames = array();
		foreach($arrAddons as $addon){
						
			$name = $addon->getName();
			$arrNames[$name] = true;
		}
		
		$arrWebAddons = array();
		
		$arrWebNames = array();
		foreach($arrCatalogAddons as $addonName => $addon){
			
			$name = UniteFunctionsUC::getVal($addon, "name");
			if(empty($name))
				$name = $addonName;
				
			$name2 = null;
			if(!empty($addonType))
				$name2 = $name."_".$addonType;
				
			if(!empty($name2) && isset($arrNames[$name2]))
				continue;
				
			if(isset($arrNames[$name]))
				continue;
			
			if(empty($name))
				continue;
			
			$addon["isweb"] = true;
			if(!isset($addon["name"]))
				$addon["name"] = $name;
			
				
			$arrWebNames[] = $name;
			$arrWebAddons[$name] = $addon;
		}
		
		//exclude web addons existing in another folders
		$arrWebAddons = $this->filterWebAddonsByInstalled($arrWebAddons, $arrWebNames);
		
		foreach($arrWebAddons as $addon)
			$arrAddons[] = $addon;
		
		
		return($arrAddons);
	}
	
	
	/**
	 * merge categories list with catalog
	 * for manager
	 */
	public function mergeCatsWithCatalog($arrCats){
		
		if($this->isCatalogExists() == false)
			$this->checkUpdateCatalog();
		
		$arrWebCatalog = $this->getCatalogArray_addons();
		
		if(empty($arrWebCatalog))
			return($arrCats);

		$arrCats = UniteFunctionsUC::arrayToAssoc($arrCats,"title");
		
		foreach($arrWebCatalog as $dir=>$addons){
			$arrDir = array();
			
			if(empty($addons))
				$addons = array();
			
			if(isset($arrCats[$dir]) == false)
				$arrCats[$dir] = array(
					"isweb"=>true,
					"title"=>$dir,
					"num_addons"=>count($addons)
				);			
			
			//add number of web addons
		}
		
		
		return($arrCats);
	}
	
	
	
	/**
	 * get category addons array from catalog
	 */
	public function getArrCatAddons($title, $objAddonsType){
				
		$arrWebCatalog = $this->getCatalogArray($objAddonsType);
				
		if(empty($arrWebCatalog))
			return(array());
		
		$arrCatAddons = UniteFunctionsUC::getVal($arrWebCatalog, $title);
		
		return($arrCatAddons);
	}
	
	
	/**
	 * filter web addons with installed addons
	 */
	private function filterWebAddonsByInstalled($arrWebAddons, $arrWebNames){
		
		if(empty($arrWebAddons))
			return($arrWebAddons);
		
		$objAddons = new UniteCreatorAddons();
		
		$params = array();
		$params["filter_names"] = $arrWebNames;
		$arrInstalledAddons = $objAddons->getArrAddonsShort("", $params);
		
		if(empty($arrInstalledAddons))
			return($arrWebAddons);
		
		foreach($arrInstalledAddons as $addon){
			$name = UniteFunctionsUC::getVal($addon, "name");
			unset($arrWebAddons[$name]);
		}
		
		return($arrWebAddons);
	}
	
	

	/**
	 * get imported addon data
	 */
	protected function getImportedAddonData($addonType, $addonID){
		
		if($addonType != GlobalsUC::ADDON_TYPE_SHAPE_DEVIDER)
			return(array());
		
		$objShapes = new UniteShapeManagerUC();
		$shapeBGContent = $objShapes->getShapeBGContentBYAddonID($addonID);
		
		$data = array();
		$data["shape_content"] = $shapeBGContent;
		
		return($data);
	}
	
	
	/**
	 * install catalog addon
	 */
	public function installCatalogAddonFromData($data){
		
		$name = UniteFunctionsUC::getVal($data, "name");
		$cat = UniteFunctionsUC::getVal($data, "cat");
		$addonType = UniteFunctionsUC::getVal($data, "type");
		
		$objAddonType = UniteCreatorAddonType::getAddonTypeObject($addonType);
		
		$catalogAddonType = $objAddonType->catalogKey;
		
		$apiData = array();
		$apiData["name"] = $name;
		$apiData["cat"] = $cat;
		$apiData["type"] = $catalogAddonType;
		
		$zipContent = $this->callAPI("get_addon_zip", $apiData, true);
		
		//save to folder
		$filename = $name.".zip";
		$filepath = GlobalsUC::$path_cache.$filename;
		UniteFunctionsUC::writeFile($zipContent, $filepath);
				
		$exporter = new UniteCreatorExporter();
				
		if($objAddonType->isBasicType == false){
						
			$addonType = $objAddonType->typeName;
			$exporter->setMustImportAddonType($addonType);
		}
		
		$exporter->import(null, $filepath);
		
		$importedAddonID = $exporter->getImportedAddonID();
		
		$objAddon = new UniteCreatorAddon();
		$objAddon->initByID($importedAddonID);
		
		$alias = $objAddon->getAlias();
		
		$response = array();
		$response["addonid"] = $importedAddonID;
		$response["alias"] = $alias;
		
		$addonData = $this->getImportedAddonData($addonType, $importedAddonID);
		if(!empty($addonData))
			$response = array_merge($response, $addonData);
		
		return($response);
	}

	
	/**
	 * install catalog addon
	 */
	public function installCatalogPageFromData($data){
		
		$name = UniteFunctionsUC::getVal($data, "name");
		$params = UniteFunctionsUC::getVal($data, "params");
		$addonType = UniteFunctionsUC::getVal($data, "type");		
		
		$objAddonType = UniteCreatorAddonType::getAddonTypeObject($addonType);
		
		$catalogAddonType = $objAddonType->catalogKey;
		
		if(empty($params))
			$params = array();
		
		$layoutID = UniteFunctionsUC::getVal($params, "layout_id");
		if(empty($layoutID))
			$layoutID = null;
		
		$apiData = array();
		$apiData["name"] = $name;
		$apiData["type"] = $catalogAddonType;
		
		$zipContent = $this->callAPI("get_page_zip", $data, true);
		
		//save to folder
		$filename = $name.".zip";
		$filepath = GlobalsUC::$path_cache.$filename;
		UniteFunctionsUC::writeFile($zipContent, $filepath);
		
		$exporter = new UniteCreatorLayoutsExporter();
		$importedLayoutID = $exporter->import($filepath, $layoutID, true, $params);
		
		if(file_exists($filepath))
			@unlink($filepath);
		
		$arrResponse = array();
		$arrResponse["layoutid"] = $importedLayoutID;
		
		return($arrResponse);
	}
	
	
}