<?php

/**
 * @package Unlimited Elements
 * @author UniteCMS http://unitecms.net
 * @copyright Copyright (c) 2016 UniteCMS
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined ('UNLIMITED_ELEMENTS_INC') or die ('restricted aceess');

class UniteCreatorToolsetIntegrate{
	
	private static $objTypesService;
	private static $prefix = "cf_";
	const GROUP_PREFIX = "_repeatable_group_";
	
	/**
	 * return if toolst exists
	 */
	public static function isToolsetExists(){
			
		if(function_exists("wpcf_admin_fields_get_groups"))
			return(true);
		
		return(false);
	}
	
	/**
	 * get fields by post type
	 */
	private function getFieldsByPostType($postType){
			
		$arrFields = wpcf_admin_fields_get_active_fields_by_post_type($postType);
				
		return($arrFields);
	}
	
	
	/**
	 * get post field data
	 */
	private function getPostFieldData($post, $fieldID){
		
		if(empty(self::$objTypesService))
			self::$objTypesService = new Types_Field_Service( false );
		
		$content = self::$objTypesService->render_frontend( new Types_Field_Gateway_Wordpress_Post(), $post, $fieldID);
		
		return($content);
	}
	
	/**
	 * get field key suffix by type
	 */
	private function getFieldKeySuffix($field){
		
		$suffix = "";
		
		$type = UniteFunctionsUC::getVal($field, "type");
		
		switch($type){
			case "audio":
			case "email":
			case "embed":
			case "image":
			case "textarea":
			case "skype":
			case "wysiwyg":
				$suffix = "|raw";
			break;
		}
		
		return($suffix);
	}
	
	/**
	 * get group field data by name
	 */
	private function getGroupFieldSlug($fieldName){
		
		if(strpos($fieldName, self::GROUP_PREFIX) === false)
			return(null);
			
		$groupID = str_replace(self::GROUP_PREFIX, "", $fieldName);
		if(is_numeric($groupID) == false)
			return(null);
			
		$arrGroup = wpcf_admin_fields_get_group($groupID);
		
		if(empty($arrGroup))
			return(null);

			
		return($arrGroup);
	}
	
	/**
	 * get group items posts
	 */
	private function getGroupItemsPosts($arrGroup, $postID){
		
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $post
	 * @param unknown_type $gruopID
	 */
	private function getPostFieldGroupData($post, $gruopID){

		return("");
		
		/*
		$groupFields = wpcf_admin_fields_get_fields_by_group( $gruopID, 'slug', false, false, false, $postType);

		if(empty(self::$objTypesService))
			self::$objTypesService = new Types_Field_Service( false );
			
		
		/*		not working!		
		try{
			$child_posts = toolset_get_related_posts( 230, array( 'query_by_role' => 'parent', 'return' => 'post_object' ) ,"parent");
		}catch(Exception $e){
			dmp($e);
			exit();
		}
		*/				
		//$content = self::$objTypesService->render_frontend( new Types_Field_Gateway_Wordpress_Post(), $post, $fieldID);		
	}
	
	
	/**
	 * get post fields with data
	 */
	public function getPostFieldsWidthData($postID, $returnKeysOnly = false){
		
		$isExists = self::isToolsetExists();
		
		if(!$isExists)
			return(array());
			
		$post = get_post($postID);
		if(empty($post))
			return(array());
		
		$postType = $post->post_type;
		$arrFields = $this->getFieldsByPostType($postType);
		
		
		$arrData = array();
		
		foreach($arrFields as $fieldID => $field){
						
			$arrGroup = $this->getGroupFieldSlug($fieldID);
			
			if(!empty($arrGroup)){
				$fieldID = UniteFunctionsUC::getVal($arrGroup, "slug");
			}
			
			$fieldKey = self::$prefix.$fieldID;
			$fieldKey = str_replace("-", "_", $fieldKey);
			
			if($returnKeysOnly == true){
				
				$fieldKeySuffix = $this->getFieldKeySuffix($field);
				
				$arrData[] = $fieldKey.$fieldKeySuffix;
				continue;
			}
			
			//get content
			
			if(!empty($arrGroup)){
				$gruopID = UniteFunctionsUC::getVal($arrGroup, "id");
				$fieldContent = $this->getPostFieldGroupData($post, $gruopID);
			}
			else
				$fieldContent = $this->getPostFieldData($post, $fieldID);
			
			
			$arrData[$fieldKey] = $fieldContent;				
			
		}
		
		return($arrData);
	}
	
	
	/**
	 * get post fields keys
	 */
	public function getPostFieldsKeys($postID){
		
		$arrKeys = $this->getPostFieldsWidthData($postID, true);
		
		
		return($arrKeys);
	}
	
	
}