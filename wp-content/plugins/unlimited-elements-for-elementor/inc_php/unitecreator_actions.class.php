<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorActions{

	
	/**
	 * on update layout response, function for override
	 */
	protected function onUpdateLayoutResponse($response){
		
		$isUpdate = $response["is_update"];
		
		//create
		if($isUpdate == false){
			
			HelperUC::ajaxResponseData($response);
			
		}else{
			//update
			
			$message = $response["message"];
			$pageName = UniteFunctionsUC::getVal($response, "page_name");
			
			$arrData = array();
			if(!empty($pageName))
				$arrData["page_name"] = $pageName;
			
			HelperUC::ajaxResponseSuccess($message,$arrData);
		}
		
	}
	
	/**
	 * get data array from request
	 */
	private function getDataFromRequest(){
		
		$data = UniteFunctionsUC::getPostGetVariable("data","",UniteFunctionsUC::SANITIZE_NOTHING);
		if(empty($data))
			$data = $_REQUEST;
		
		if(is_string($data)){
						
			$arrData = (array)json_decode($data);
			
			if(empty($arrData)){
				$arrData = stripslashes(trim($data));
				$arrData = (array)json_decode($arrData);
			}
						
			$data = $arrData;
		}
		
		return($data);
	}
	
		
	
	/**
	 * on ajax action
	 */
	public function onAjaxAction(){
		
		$actionType = UniteFunctionsUC::getPostGetVariable("action","",UniteFunctionsUC::SANITIZE_KEY);
		
		if($actionType != GlobalsUC::PLUGIN_NAME."_ajax_action")
			return(false);
		
		$operations = new ProviderOperationsUC();
		
		$addons = new UniteCreatorAddons();
		$assets = new UniteCreatorAssetsWork();
		$categories = new UniteCreatorCategories();
		$layouts = new UniteCreatorLayouts();
		$webAPI = new UniteCreatorWebAPI();
		
		
		$action = UniteFunctionsUC::getPostGetVariable("client_action","",UniteFunctionsUC::SANITIZE_KEY);
		
		//go to front
		switch($action){
			case "send_form":
				$this->onAjaxFrontAction();
				return(false);
			break;
		}
		
		
		$data = $this->getDataFromRequest();
				
		$addonType = $addons->getAddonTypeFromData($data);
		
		
		$data = UniteFunctionsUC::convertStdClassToArray($data);
		
		$data = UniteProviderFunctionsUC::normalizeAjaxInputData($data);
						
		try{
		
			if(method_exists("UniteProviderFunctionsUC", "verifyNonce")){
				$nonce = UniteFunctionsUC::getPostGetVariable("nonce","",UniteFunctionsUC::SANITIZE_NOTHING);
				UniteProviderFunctionsUC::verifyNonce($nonce);
			}
			switch($action){
				
				case "remove_category":
					$response = $categories->removeFromData($data);
				
					HelperUC::ajaxResponseSuccess(esc_html__("The category deleted successfully","unlimited_elements"),$response);
					break;
				case "update_category":
					$categories->updateFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Category updated","unlimited_elements"));
					break;
				case "update_cat_order":
					$categories->updateOrderFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Order updated","unlimited_elements"));
				break;
				case "get_category_settings_html":
					
					$manager = UniteCreatorManager::getObjManagerByAddonType($addonType);
					
					$responeData = $manager->getCatSettingsHtmlFromData($data);
					HelperUC::ajaxResponseData($responeData);
				break;
				case "get_cat_addons":
										
					$manager = UniteCreatorManager::getObjManagerByAddonType($addonType, $data);
					
					$responeData = $manager->getCatAddonsHtmlFromData($data);
					
					HelperUC::ajaxResponseData($responeData);
				break;
				case "get_layouts_params_settings_html":
					
					$manager = UniteCreatorManager::getObjManagerByAddonType($addonType, $data);
								
					$responseData = $manager->getAddonPropertiesDialogHtmlFromData($data);
					
					HelperUC::ajaxResponseData($responseData);
					
				break;
				case "get_catlist":
					
					$manager = UniteCreatorManager::getObjManagerByAddonType($addonType, $data);
					
					$responeData = $manager->getCatListFromData($data);
					
					HelperUC::ajaxResponseData($responeData);
				break;
				case "get_layouts_categories":
					$responeData = $categories->getLayoutsCatsListFromData($data);
					HelperUC::ajaxResponseData($responeData);
				break;
				case "update_addon":
					$response = $addons->updateAddonFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Updated.","unlimited_elements"),$response);
				break;
				case "get_addon_bulk_dialog":
					$response = $operations->getAddonBulkDialogFromData($data);
					HelperUC::ajaxResponseData($response);
				break;
				case "update_addons_bulk":
					$addons->updateAddonsBulkFromData($data);
					$response = $operations->getAddonBulkDialogFromData($data);
					HelperUC::ajaxResponseData($response);
				break;
				case "delete_addon":
					$addons->deleteAddonFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("The addon deleted successfully","unlimited_elements"));
				break;
				case "add_category":
					$catData = $categories->addFromData($data);
					HelperUC::ajaxResponseData($catData);
				break;
				case "add_addon":
					
					if(GlobalsUC::$permisison_add == false)
						UniteFunctionsUC::throwError("Operation not permitted");
										
					$response = $addons->createFromManager($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Addon added successfully","unlimited_elements"), $response);
				break;
				case "update_addon_title":
					$addons->updateAddonTitleFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Addon updated successfully","unlimited_elements"));
				break;
				case "update_addons_activation":
					$addons->activateAddonsFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Addons updated successfully","unlimited_elements"));
				break;
				case "remove_addons":
					$response = $addons->removeAddonsFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Addons Removed","unlimited_elements"), $response);
				break;
				case "update_addons_order":
					$addons->saveOrderFromData($data);

					HelperUC::ajaxResponseSuccess(esc_html__("Order Saved","unlimited_elements"));
				break;
				case "update_layouts_order":
					$layouts->updateOrderFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Order Saved","unlimited_elements"));
				break;
				case "move_addons":
					$response = $addons->moveAddonsFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Done Operation","unlimited_elements"),$response);
				break;
				case "duplicate_addons":
					$response = $addons->duplicateAddonsFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Duplicated Successfully","unlimited_elements"),$response);
				break;
				case "get_addon_config_html":
					
					$response = $addons->getAddonConfigHTML($data);
					
					HelperUC::ajaxResponseData($response);
				break;
				case "get_addon_settings_html":
					
					$html = $addons->getAddonSettingsHTMLFromData($data);
					HelperUC::ajaxResponseData(array("html"=>$html));
				break;
				case "get_addon_item_settings_html":
				
					$html = $addons->getAddonItemsSettingsHTMLFromData($data);
					HelperUC::ajaxResponseData(array("html"=>$html));
				break;
				case "get_addon_editor_data":
					$response = $addons->getAddonEditorData($data);
					HelperUC::ajaxResponseData($response);
				break;
				case "get_addon_output_data":
					$response = $addons->getLayoutAddonOutputData($data);
					
					HelperUC::ajaxResponseData($response);
				break;
				case "show_preview":
					$addons->showAddonPreviewFromData($data);
					exit();
				break;
				case "save_addon_defaults":
					$addons->saveAddonDefaultsFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Saved","unlimited_elements"));
				break;
				case "save_test_addon":
					$addons->saveTestAddonData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Saved","unlimited_elements"));
				break;
				case "get_test_addon_data":
					$response = $addons->getTestAddonData($data);
					HelperUC::ajaxResponseData($response);
				break;
				case "delete_test_addon_data":
					$addons->deleteTestAddonData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Test data deleted","unlimited_elements"));
				break;
				case "export_addon":
					$addons->exportAddon($data);
					exit();
				break;
				case "export_cat_addons":
					$addons->exportCatAddons($data);
				break;
				case "import_addons":
					$response = $addons->importAddons($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Addons Imported","unlimited_elements"),$response);
				break;
				case "import_layouts":
					$urlRedirect = $layouts->importLayouts($data);
					
					if(!empty($urlRedirect))
						HelperUC::ajaxResponseSuccessRedirect(HelperUC::getText("layout_imported"), $urlRedirect);
					else
						HelperUC::ajaxResponseSuccess(HelperUC::getText("layout_imported"));
					
				break;
				case "get_version_text":
					$content = HelperHtmlUC::getVersionText();
					HelperUC::ajaxResponseData(array("text"=>$content));
				break;
				case "update_plugin":
				
					if(method_exists("UniteProviderFunctionsUC", "updatePlugin"))
						UniteProviderFunctionsUC::updatePlugin();
					else{
						echo "Functionality Don't Exists";
						exit();
					}
				
				break;
				case "update_general_settings":
					$operations->updateGeneralSettingsFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Settings Saved","unlimited_elements"));
				break;
				case "update_global_layout_settings":
					
					UniteCreatorLayout::updateLayoutGlobalSettingsFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Settings Saved","unlimited_elements"));
				break;
				case "update_layout":
					
					$response = $layouts->updateLayoutFromData($data);
					
					$this->onUpdateLayoutResponse($response);
				break;
				case "update_layout_category":
					$response = $layouts->updateLayoutCategoryFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Category Updated","unlimited_elements"));
				break;
				
				case "update_layout_params":
					
					$response = $layouts->updateParamsFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Layout Updated","unlimited_elements"), $response);
				break;
				case "delete_layout":
					
					$layouts->deleteLayoutFromData($data);
					$urlLayouts = HelperUC::getViewUrl_LayoutsList();
					
					HelperUC::ajaxResponseSuccessRedirect(HelperUC::getText("layout_deleted"), $urlLayouts);
					
				break;
				case "duplicate_layout":
					
					$urlRedirect = $layouts->duplicateLayoutFromData($data);
					if(empty($urlRedirect))	
						$urlRedirect = HelperUC::getViewUrl_LayoutsList();
					
					HelperUC::ajaxResponseSuccessRedirect(HelperUC::getText("layout_duplicated"), $urlRedirect);
					
				break;
				case "export_layout":
					$layouts->exportLayout();
					exit();
				break;
				case "activate_product":
					
					$expireDays = $webAPI->activateProductFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Product Activated", "unlimited_elements"), array("expire_days"=>$expireDays));
				break;
				case "deactivate_product":
					
					$webAPI->deactivateProduct($data);
					
					HelperUC::ajaxResponseSuccess("Product Deactivated, please refresh the page");
				break;
				case "check_catalog":
					$isForce = UniteFunctionsUC::getVal($data, "force");
					$isForce = UniteFunctionsUC::strToBool($isForce);
					
					$response = $webAPI->checkUpdateCatalog();
					HelperUC::ajaxResponseData($response);
				break;
				case "install_catalog_addon":
										
					$response = $webAPI->installCatalogAddonFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Addon Installed", "unlimited_elements"), $response);
				break;
				case "install_catalog_page":
					$arrResponse = $webAPI->installCatalogPageFromData($data);
					HelperUC::ajaxResponseSuccess(esc_html__("Layouts Installed", "unlimited_elements"), $arrResponse);
				break;
				case "update_addon_from_catalog":	//by id
					$urlRedirect = $addons->updateAddonFromCatalogFromData($data);
					HelperUC::ajaxResponseSuccessRedirect(esc_html__("Addon Updated","unlimited_elements"), $urlRedirect);
				break;
				case "get_shapes_css":
					
					$objShapes = new UniteShapeManagerUC();
					$objShapes->outputCssShapes();
					exit();
				break;
				case "save_screenshot":
					$response = $operations->saveScreenshotFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Screenshot Saved", "unlimited_elements"), $response);
				break;
				case "save_section_tolibrary":
					
					$response = $layouts->saveSectionToLibraryFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Section Saved", "unlimited_elements"), $response);
					
				break;
				case "get_grid_import_layout_data":
					
					$response = $layouts->getLayoutGridDataForEditor($data);
					
					HelperUC::ajaxResponseData($response);
					
				break;
				case "save_custom_settings":
					
					$operations->updateCustomSettingsFromData($data);
					
					HelperUC::ajaxResponseSuccess(esc_html__("Settings Saved", "unlimited_elements"));
					
				break;
				case "get_posts_list_forselect":
					
					$arrPostList = $operations->getPostListForSelectFromData($data);
					
					HelperUC::ajaxResponseData($arrPostList);
					
				break;
				case "get_post_child_params":
					
					$response = $operations->getPostAttributesFromData($data);
					
					HelperUC::ajaxResponseData($response);
					
				break;
				default:
					
					//check assets
					$found = $assets->checkAjaxActions($action, $data);

					if(!$found)
						$found = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_ADMIN_AJAX_ACTION, $found, $action, $data);
										
					if(!$found)
						HelperUC::ajaxResponseError("wrong ajax action: <b>$action</b> ");
				break;
			}
		
		}
		catch(Exception $e){
			$message = $e->getMessage();
		
			$errorMessage = $message;
			if(GlobalsUC::SHOW_TRACE == true){
				$trace = $e->getTraceAsString();
				$errorMessage = $message."<pre>".$trace."</pre>";
			}
		
			HelperUC::ajaxResponseError($errorMessage);
		}
		
		//it's an ajax action, so exit
		HelperUC::ajaxResponseError("No response output on <b> $action </b> action. please check with the developer.");
		exit();
		
	}
	
	
	
	/**
	 * on ajax action
	 */
	public function onAjaxFrontAction(){
		
		$actionType = UniteFunctionsUC::getPostGetVariable("action","",UniteFunctionsUC::SANITIZE_KEY);
		
		switch($actionType){
			case GlobalsUC::PLUGIN_NAME."_ajax_action":
			case GlobalsUC::PLUGIN_NAME."_ajax_action_front":
			break;
			default:
				return(false);
			break;
		}
				
		$action = UniteFunctionsUC::getPostGetVariable("client_action","",UniteFunctionsUC::SANITIZE_KEY);
		$data = $this->getDataFromRequest();
		
		try{
					
			switch($action){
				case "send_form":
					
					$objForm = new UniteCreatorForm();
					$objForm->sendFormFromData($data);
					
					HelperUC::ajaxResponseSuccess("Form Sent");
					
				break;
				default:
					
					HelperUC::ajaxResponseError("wrong ajax action: <b>$action</b> ");
				break;
			}
			
		}
		catch(Exception $e){
			$message = $e->getMessage();
		
			$errorMessage = $message;
			if(GlobalsUC::SHOW_TRACE == true){
				$trace = $e->getTraceAsString();
				$errorMessage = $message."<pre>".$trace."</pre>";
			}
		
			HelperUC::ajaxResponseError($errorMessage);
		}
		
		
		//it's an ajax action, so exit
		HelperUC::ajaxResponseError("No response output on <b> $action </b> action. please check with the developer.");
		exit();		
	}
	
	
	
}