<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorSettings extends UniteCreatorSettingsWork{

	
	/**
	 * add settings provider types
	 */
	protected function addSettingsProvider($type, $name,$value,$title,$extra ){
		
		$isAdded = false;
		
		return($isAdded);
	}
	
	/**
	 * show taxanomy
	 */
	private function showTax(){
										
		$showTax = UniteFunctionsUC::getGetVar("maxshowtax", "", UniteFunctionsUC::SANITIZE_NOTHING);
		$showTax = UniteFunctionsUC::strToBool($showTax);
		
		if($showTax == true){
			
			$args = array("taxonomy"=>"");
			$cats = get_categories($args);
			
			$arr1 = UniteFunctionsWPUC::getTaxonomiesWithCats();
			
			
			$arrPostTypes = UniteFunctionsWPUC::getPostTypesAssoc();
			$arrTax = UniteFunctionsWPUC::getTaxonomiesWithCats();
			$arrCustomTypes = get_post_types(array('_builtin' => false));
			
			$arr = get_taxonomies();
			
			$taxonomy_objects = get_object_taxonomies( 'post', 'objects' );
   			dmp($taxonomy_objects);
   			
			dmp($arrCustomTypes);
			dmp($arrPostTypes);
			exit();
		}
		
	}
	
	
	/**
	 * get categories from all post types
	 */
	protected function getCategoriesFromAllPostTypes($arrPostTypes){
		
		if(empty($arrPostTypes))
			return($array);

		$arrAllCats = array();
		$arrAllCats[__("All Categories", "unlimited_elements")] = "all";
		
		foreach($arrPostTypes as $name => $arrType){
		
			if($name == "page")
				continue;
			
			$postTypeTitle = UniteFunctionsUC::getVal($arrType, "title");
			
			$cats = UniteFunctionsUC::getVal($arrType, "cats");
			
			if(empty($cats))
				continue;
			
			foreach($cats as $catID => $catTitle){
				
				if($name != "post")
					$catTitle = $catTitle." ($postTypeTitle type)";
				
				$arrAllCats[$catTitle] = $catID;
				
			}
			
		}
		
		
		return($arrAllCats);
	}
	
	/**
	 * add post list picker
	 */
	protected function addPostsListPicker($name,$value,$title,$extra){
		
		$simpleMode = UniteFunctionsUC::getVal($extra, "simple_mode");
		$simpleMode = UniteFunctionsUC::strToBool($simpleMode);
		
		$allCatsMode = UniteFunctionsUC::getVal($extra, "all_cats_mode");
		$allCatsMode = UniteFunctionsUC::strToBool($allCatsMode);
		
		$addCurrentPosts = UniteFunctionsUC::getVal($extra, "add_current_posts");
		$addCurrentPosts = UniteFunctionsUC::strToBool($addCurrentPosts);
		
		$arrPostTypes = UniteFunctionsWPUC::getPostTypesWithCats(GlobalsProviderUC::$arrFilterPostTypes);
		
		$arrGlobalElementorCondition = array();
		
		
		//fill simple types
		$arrTypesSimple = array();
		
		if($simpleMode)
			$arrTypesSimple = array("Post"=>"post","Page"=>"page");
		else{
			
			foreach($arrPostTypes as $arrType){
				
				$postTypeName = UniteFunctionsUC::getVal($arrType, "name");
				$postTypeTitle = UniteFunctionsUC::getVal($arrType, "title");
				
				if(isset($arrTypesSimple[$postTypeTitle]))
					$arrTypesSimple[$postTypeName] = $postTypeName;
				else
					$arrTypesSimple[$postTypeTitle] = $postTypeName;
			}
			
		}
		
		//----- posts source ----
		//UniteFunctionsUC::showTrace();
		
		if($addCurrentPosts == true){
			
			$params = array();
			$params["origtype"] = UniteCreatorDialogParam::PARAM_DROPDOWN;
			$params["description"] = esc_html__("Choose the source of the posts list", "unlimited_elements");
			
			
			$source = UniteFunctionsUC::getVal($value, $name."_source", "current");
			$arrSourceOptions = array();
			$arrSourceOptions[__("Current Posts", "unlimited_elements")] = "current";
			$arrSourceOptions[__("Choost Custom Posts", "unlimited_elements")] = "custom";
			
			$this->addSelect($name."_source", $arrSourceOptions, esc_html__("Posts Source", "unlimited_elements"), $source, $params);
			
			$arrGlobalElementorCondition = array();
			
			$arrGlobalElementorCondition = array(
				$name."_source" => "custom",
			);
			
		}
		
		//----- post type -----
		
		$postType = UniteFunctionsUC::getVal($value, $name."_posttype", "post");
		
		$params = array();
		
		if($simpleMode == false){
			$params["datasource"] = "post_type";
			$params[UniteSettingsUC::PARAM_CLASSADD] = "unite-setting-post-type";
			
			$dataCats = UniteFunctionsUC::encodeContent($arrPostTypes);
			
			$params[UniteSettingsUC::PARAM_ADDPARAMS] = "data-arrposttypes='$dataCats' data-settingtype='select_post_type'";
		}
		
		$params["origtype"] = "uc_select_special";
		$params["description"] = esc_html__("Select which Post Type or Custom Post Type you wish to display", "unlimited_elements");
		
		if(!empty($arrGlobalElementorCondition))
			$params["elementor_condition"] = $arrGlobalElementorCondition;
		
		
		$this->addSelect($name."_posttype", $arrTypesSimple, esc_html__("Post Type", "unlimited_elements"), $postType, $params);
		
		
		//----- add categories -------
		
		$arrCats = array();
		
		if($simpleMode == true){
			
			$arrCats = $arrPostTypes["post"]["cats"];
			$arrCats = array_flip($arrCats);
			$firstItemValue = reset($arrCats);
			
		}else if($allCatsMode == true){
			
			$arrCats = $this->getCategoriesFromAllPostTypes($arrPostTypes);
			$firstItemValue = reset($arrCats);
			
		}else{
			$firstItemValue = "";
		}
		
		//--------- post category -----------
		
		
		$category = UniteFunctionsUC::getVal($value, $name."_category", $firstItemValue);
		
		$params = array();
		
		if($simpleMode == false){
			$params["datasource"] = "post_category";
			$params[UniteSettingsUC::PARAM_CLASSADD] = "unite-setting-post-category";
		}
		
		$params["origtype"] = UniteCreatorDialogParam::PARAM_DROPDOWN;
		$params["is_multiple"] = true;
		
		$params["description"] = esc_html__("Filter Posts by Specific Category", "unlimited_elements");
		
		if(!empty($arrGlobalElementorCondition))
			$params["elementor_condition"] = $arrGlobalElementorCondition;
		
		
		$this->addMultiSelect($name."_category", $arrCats, esc_html__("Post Category", "unlimited_elements"), $category, $params);
		
		
		//------- max items --------
		
		$params = array("unit"=>"posts");
		$maxItems = UniteFunctionsUC::getVal($value, $name."_maxitems", 10);
		$params["origtype"] = UniteCreatorDialogParam::PARAM_TEXTFIELD;
		
		$params["description"] = "Enter how many Posts you wish to display, -1 for unlimited";
		
		if(!empty($arrGlobalElementorCondition))
			$params["elementor_condition"] = $arrGlobalElementorCondition;
		
		$this->addTextBox($name."_maxitems", $maxItems, esc_html__("Max Posts", "unlimited_elements"), $params);
		
		
		//----- orderby --------
		
		$arrOrder = UniteFunctionsWPUC::getArrSortBy();
		$arrOrder = array_flip($arrOrder);
		
		$arrDir = UniteFunctionsWPUC::getArrSortDirection();
		$arrDir = array_flip($arrDir);
				
		//---- orderby1
		
		$params = array();
		
		//$params[UniteSettingsUC::PARAM_ADDFIELD] = $name."_orderdir1";
		
		$orderBY = UniteFunctionsUC::getVal($value, $name."_orderby", UniteFunctionsWPUC::SORTBY_ID);
		$params["origtype"] = UniteCreatorDialogParam::PARAM_DROPDOWN;
		$params["description"] = esc_html__("Select how you wish to order posts", "unlimited_elements");
		
		if(!empty($arrGlobalElementorCondition))
			$params["elementor_condition"] = $arrGlobalElementorCondition;
		
		
		$this->addSelect($name."_orderby", $arrOrder, __("Order By", "unlimited_elements"), $orderBY, $params);
		
		
		
		//--- meta value param -------
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_TEXTFIELD;
		$params["class"] = "alias";
		
		$arrCondition = $arrGlobalElementorCondition;
		$arrCondition[$name."_orderby"] = array(UniteFunctionsWPUC::SORTBY_META_VALUE, UniteFunctionsWPUC::SORTBY_META_VALUE_NUM);
		
		$params["elementor_condition"] = $arrCondition;
		
		$this->addTextBox($name."_orderby_meta_key1", "" , __("&nbsp;&nbsp;Custom Field Name","unlimited_elements"), $params);

		$this->addControl($name."_orderby", $name."_orderby_meta_key1", "show", UniteFunctionsWPUC::SORTBY_META_VALUE.",".UniteFunctionsWPUC::SORTBY_META_VALUE_NUM);
		
		//---- order dir -----
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_DROPDOWN;
		$params["description"] = esc_html__("Select order direction. Descending A-Z or Accending Z-A", "unlimited_elements");
		
		if(!empty($arrGlobalElementorCondition))
			$params["elementor_condition"] = $arrGlobalElementorCondition;
		
		$orderDir1 = UniteFunctionsUC::getVal($value, $name."_orderdir1", UniteFunctionsWPUC::ORDER_DIRECTION_DESC );
		$this->addSelect($name."_orderdir1", $arrDir, __("&nbsp;&nbsp;Order By Direction", "unlimited_elements"), $orderDir1, $params);
		
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_HR;
		
		$this->addHr("", $params);
		
	}
	
	
}