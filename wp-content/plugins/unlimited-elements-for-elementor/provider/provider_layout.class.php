<?php

/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorLayout extends UniteCreatorLayoutWork{
	
	
	private $post;
	private $metaData;
	
	
	/**
	 * construct the layout
	 */
	public function __construct(){
		
		parent::__construct();
	}
	
	/**
	 * init by post object
	 */
	public function initByPost($post){
		
		UniteFunctionsUC::validateNotEmpty($post," post object");
		
		$postID = $post->ID;
		
		$title = $post->post_title;
		$name = $post->post_name;
				
		$postType = $post->post_type;
		
		$this->post = $post;
				
		//init extra params
		if(empty($this->extraParams))
			$this->extraParams = array();
		
		$this->extraParams["post_type"] = $postType;
		
		//get layout type
		$layoutType = "";
		
		if($postType == GlobalsProviderUC::POST_TYPE_LAYOUT){
			
			$layoutType = get_post_meta($postID, GlobalsProviderUC::META_KEY_LAYOUT_TYPE, true);
			
			if(empty($layoutType) || is_string($layoutType) == false)
				$layoutType = "";
		}
		
		$layoutData = get_post_meta($postID, GlobalsProviderUC::META_KEY_LAYOUT_DATA);
		
		//support legacy
		if($postType == "uc_layout" && empty($layoutData)){
			
			$layoutData = get_post_meta($postID, "layout_data");
			if(empty($layoutData))
				$layoutData = get_post_meta($postID, "blox_layout_data");
		}
		
		
		$this->metaData = $layoutData;
		
		if(!empty($layoutData) && is_array($layoutData))
			$layoutData = $layoutData[0];
		
		//get params
		$params = get_post_meta($postID, GlobalsProviderUC::META_KEY_LAYOUT_PARAMS, true);
		
		$catID = get_post_meta($postID, GlobalsProviderUC::META_KEY_CATID, true);		
		if(empty($catID))
			$catID = 0;
		
		if(!empty($params) && is_array($params))
			$params = $params[0];
		
		$record = array();
		$record["ordering"] = 0;
		$record["title"] = $title;
		$record["layout_data"] = $layoutData;
		$record["layout_type"] = $layoutType;
		$record["name"] = $name;
		$record["params"] = $params;
		$record["id"] = $postID;
		$record["catid"] = $catID;
		
		
		$this->initByRecord($record);
	}
	
	
	/**
	 * init by post id
	 */
	public function initByID($id){
		
		if($id === "current_post"){
			$id = null;
			$post = get_post();
			if(empty($post))
				UniteFunctionsUC::throwError("Current post not found");
			
			$this->id = $post->ID;
			
		}else{
			$id = (int)$id;
			if(empty($id))
				UniteFunctionsUC::throwError("Empty layout ID");
			
			$post = @get_post($id);
			if(empty($post))
				UniteFunctionsUC::throwError("layout with id: $id not found");
			
			$this->id = $id;
		}
		
		
		$this->initByPost($post);
	}
		
	
	/**
	 * update layout in db
	 */
	public function createLayoutInDB($arrInsert, $arrParams = array()){
		
		UniteFunctionsUC::validateNotEmpty($this->objLayoutType, "layout type object");
		
		//set post type
		$postType = $this->objLayoutType->postType;
		
		if(empty($postType)){
						
			if($this->objLayoutType->isBasicType == true)
				$postType = UniteFunctionsUC::getVal($arrParams, "post_type");
			else
				$postType = GlobalsProviderUC::POST_TYPE_LAYOUT;		//basic type - false (blox type)
		} 
		
					
		if(empty($postType))
			UniteFunctionsUC::throwError("You must specify post type for import layout");
		
					
			
		$title = UniteFunctionsUC::getVal($arrInsert, "title");
		$name = UniteFunctionsUC::getVal($arrInsert, "name");
		
		//for non blox layouts - take name from post title
		if($postType != GlobalsProviderUC::POST_TYPE_LAYOUT)
			$name = sanitize_title($title);
		
		$layoutData = UniteFunctionsUC::getVal($arrInsert, "layout_data");
		
		$catID = UniteFunctionsUC::getVal($arrInsert, "catid");
		
		$arrPost = array();
		$arrPost["post_title"] = $title;
		$arrPost["post_name"] = $name;
		$arrPost["post_content"] = "blox page layout";
		$arrPost["post_type"] = $postType;
		$arrPost["post_status"] = "publish";
		
		$maxOrder = UniteFunctionsWPUC::getMaxMenuOrder($postType);
		
		$arrPost["menu_order"] = $maxOrder+1;
		
		
		dmp("Add Category!!!");
		dmp($arrPost);
		exit();
		
		$postID = wp_insert_post($arrPost);
		
		if($this->objLayoutType->isBloxPage == false)
			return($postID);
		
		//update blox page related
		
		add_post_meta($postID, GlobalsProviderUC::META_KEY_BLOX_PAGE, "true");
		
		if(!empty($layoutData))
			add_post_meta($postID, GlobalsProviderUC::META_KEY_LAYOUT_DATA, $layoutData);
		
		//add layout type meta
		if($postType == GlobalsProviderUC::POST_TYPE_LAYOUT){
			
			add_post_meta($postID, GlobalsProviderUC::META_KEY_LAYOUT_TYPE, $this->objLayoutType->typeNameDistinct);
			
			if(!empty($catID))
			   add_post_meta($postID, GlobalsProviderUC::META_KEY_CATID, $catID);				
			   
		}
		
		//update page template
		if($this->objLayoutType->defaultBlankTemplate == true)
			UniteFunctionsWPUC::updatePageTemplateAttribute($postID, GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE);
		
				
		return($postID);
	}
	
	
	/**
	 * update layout in db
	 */
	public function updateLayoutInDB($arrUpdate){
		
		$postID = $this->id;
				
		//update post
		$title = UniteFunctionsUC::getVal($arrUpdate, "title");
		if($title == "Auto Draft")
			UniteFunctionsUC::throwError("Please change the title, 'Auto Draft' is a bad title :), <br><br> The page will be published when you save");
		
		
		$arrUpdatePost = array();
		
		
		//update title if needed
		if(array_key_exists("title", $arrUpdate))
			$arrUpdatePost["post_title"] = $arrUpdate["title"];
		
		$postStatus = $this->post->post_status;
		if($postStatus == "auto-draft"){
			$arrUpdatePost["post_status"] = "draft";
			
			//set import title
			$importTitle = UniteFunctionsUC::getVal($arrUpdate, "import_title");
			if(empty($importTitle)){
				$importTitle = $this->getNewLayoutTitle();
			}
			
			$arrUpdatePost["post_title"] = $importTitle;
				
		}
		
		//update post name (if title updated)
		if(isset($arrUpdatePost["post_title"])){
			$postName = $this->post->post_name;
			
			if(empty($postName))
				$arrUpdatePost["post_name"] = sanitize_title($arrUpdatePost["post_title"]);
		}
		
		
		//update post
		if(!empty($arrUpdatePost)){
						
			$arrUpdatePost["ID"] = $postID;
			
			$success = wp_update_post($arrUpdatePost);
			
			if($success == 0)
				UniteFunctionsUC::throwError("Unable to update page");
		}
		
		// --- update layout params (if exists)
		
		if(array_key_exists("params", $arrUpdate)){
						
			$params = UniteFunctionsUC::getVal($arrUpdate, "params");
			update_post_meta($postID, GlobalsProviderUC::META_KEY_LAYOUT_PARAMS, $params);
			
		}		
		
		
		// --- update layout data (if exists)
		
		if(array_key_exists("layout_data", $arrUpdate)){
						
			$layoutData = $arrUpdate["layout_data"];
			
			update_post_meta($postID, GlobalsProviderUC::META_KEY_BLOX_PAGE, "true");
			$updated = update_post_meta($postID, GlobalsProviderUC::META_KEY_LAYOUT_DATA, $layoutData);
			
			//small validation
			if($updated == false){
				$oldData = $this->getRawLayoutData();
				if($oldData != $layoutData)
					UniteFunctionsUC::throwError("Unable to update page layout data");
			}
			
			
			//update post content with stripped html
			$layoutHtml = HelperUC::outputLayout($postID, true);
			$layoutHtml = UniteFunctionsUC::getPrettyHtml($layoutHtml);
			
			$arrUpdateContent = array();
			$arrUpdateContent["ID"] = $postID;
			$arrUpdateContent["post_content"] = $layoutHtml;
			
			wp_update_post($arrUpdateContent);
		}
		
		
		
	}
	
	
	/**
	 * duplicate layout
	 */
	public function duplicate(){
		
		$this->validateInited();
		
		$newTitle = $this->getDuplicateTitle();
		$postID = $this->id;
		
		$newPostID = UniteFunctionsWPUC::duplicatePost($postID, $newTitle);
		
		return($newPostID);
	}
	
	
	/**
	 * delete layout
	 */
	public function delete(){
		
		$this->validateInited();
		
		wp_delete_post($this->id, true);
		
		delete_metadata("post", $this->id, GlobalsProviderUC::META_KEY_BLOX_PAGE);
		delete_metadata("post", $this->id, GlobalsProviderUC::META_KEY_LAYOUT_DATA);
		delete_metadata("post", $this->id, GlobalsProviderUC::META_KEY_CATID);
		delete_metadata("post", $this->id, GlobalsProviderUC::META_KEY_LAYOUT_TYPE);
		
	}
	
	
	/**
	 * check if layout exists by title
	 */
	protected function isLayoutExistsByTitle($title, $layoutType){
		
		$isExists = UniteFunctionsWPUC::isPostExistsByTitle($title);
		
		return($isExists);
	}
	
	
	/**
	 * check if layout exists by title
	 */
	protected function isLayoutExistsByName($name, $layoutType){
		
		$isExists = UniteFunctionsWPUC::isPostNameExists($name);
		
		return($isExists);
	}
	
	
	/**
	 * generate addon with content
	 */
	public function generateHtmlAddonContentForLayout($postContent){
		
		$objAddons = new UniteCreatorAddons();
		$isExists = $objAddons->isAddonExistsByName("html_editor");
		if($isExists == false)
			return(null);
		
		$objAddon = new UniteCreatorAddon();
		$objAddon->initByName("html_editor");
		
		$arrContent = array();
		$arrContent["editor"] = $postContent;
		
		$objAddon->setParamsValues($arrContent);
		$arrAddonContent = $objAddon->getDataForLayoutGrid();
		
		return($arrAddonContent);
	}
	
	
	/**
	 * check content for new layout
	 * if there is no saved content, put content from post content box
	 */
	public function checkNewPostLayoutContent(){
		
		$this->validateInited();
				
		//check meta data
		if(!empty($this->metaData))
			return(false);
		
		//get post content
		$postContent = $this->post->post_content;
		$postContent = trim($postContent);
		
		if(empty($postContent))
			return(false);
			
		//generate addon data
		$arrAddonContent = $this->generateHtmlAddonContentForLayout($postContent);
		if(empty($arrAddonContent))
			return(false);
		
		//add row to empty layout with the addon data
		$this->addRowWithHtmlAddon($arrAddonContent);
		
	}
	
}