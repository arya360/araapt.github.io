<?php

defined('UNLIMITED_ELEMENTS_INC') or die;

class UniteCreatorAddonViewProvider extends UniteCreatorAddonView{
	
	

	/**
	 * add dynamic fields child keys
	 */
	protected function addDynamicChildKeys($arrChildKeys){
		
		
		$isDynamicAddon = UniteFunctionsUC::getVal($this->addonOptions, "dynamic_addon");
		$isDynamicAddon = UniteFunctionsUC::strToBool($isDynamicAddon);
		
		if($isDynamicAddon == false)
			return($arrChildKeys);
			
		$postID = UniteFunctionsUC::getVal($this->addonOptions, "dynamic_post");
		
		if(empty($postID))
			return($arrChildKeys);
		
		$post = get_post($postID);
		
		if(empty($post))
			return($arrChildKeys);

		//add current post
		$arrPostAdditions = HelperProviderUC::getPostAdditionsArray_fromAddonOptions($this->addonOptions);
		
		//add current post child keys
		$arrChildKeys["uc_current_post"] = $this->getChildParams_post($postID, $arrPostAdditions);
		
		
		return($arrChildKeys);
	}
	
	/**
	 * add taxonomies params
	 * function for override
	 */
	protected function addTaxonomiesParams($arrParams, $postID){
		
		if(empty($postID))
			return($arrParams);

		$post = get_post($postID);
		
		if(empty($post))
			return($arrParams);

		if(Globalsuc::$inDev == false)
			return($arrParams);
			
		$arrTerms = UniteFunctionsWPUC::getPostTerms($post);
		
		
		return($arrParams);
	}
	
	
	/**
	 * add custom fields
	 */
	protected function addCustomFieldsParams($arrParams, $postID){
		
		if(empty($postID))
			return($arrParams);
		
		$isAcfExists = UniteCreatorAcfIntegrate::isAcfActive();
		
		$prefix = "cf_";
			
		//take from pods
		$isPodsExists = UniteCreatorPodsIntegrate::isPodsExists();
		
		$takeFromACF = true;
		if($isPodsExists == true){
			$arrMetaKeys = UniteFunctionsWPUC::getPostMetaKeys_PODS($postID);
			if(!empty($arrMetaKeys))
				$takeFromACF = false;
		}
		
		//take from toolset
		$isToolsetExists = UniteCreatorToolsetIntegrate::isToolsetExists();
		if($isToolsetExists == true){
			
			$objToolset = new UniteCreatorToolsetIntegrate();
			$arrMetaKeys = $objToolset->getPostFieldsKeys($postID);
			$takeFromACF = false;
		}
		
		
		//acf custom fields
		if($isAcfExists == true && $takeFromACF == true){
			
			$arrMetaKeys = UniteFunctionsWPUC::getAcfFieldsKeys($postID);
			$title = "acf field";
			
			if(empty($arrMetaKeys))
				return($arrParams);
			
			$firstKey = UniteFunctionsUC::getFirstNotEmptyKey($arrMetaKeys);
			
			foreach($arrMetaKeys as $key=>$type){
				
				//complex code (repeater) 
				
				if(is_array($type)){

					$strCode = "";
					$strCode .= "{% for item in [param_prefix].$key %}\n";
					
					$typeKeys = array_keys($type);
					
					foreach($typeKeys as $postItemKey){
												
						$strCode .= "<span> {{item.$postItemKey}} </span>\n";
					}
					
					$strCode .= "{% endfor %}\n";
					
				    $arrParams[] = $this->createChildParam($key, null, array("raw_insert_text"=>$strCode));
					
				    continue;
				}
				
				//array code 
				
				if($type == "array"){
					
					$strCode = "";
					$strCode .= "{% for value in [param_prefix].$key %}\n";
					$strCode .= "<span> {{item}} </span>\n";
					$strCode .= "{% endfor %}\n";
					
				    $arrParams[] = $this->createChildParam($key, null, array("raw_insert_text"=>$strCode));
					
					continue;
				}
				
				if($type == "empty_repeater"){
					
					$strText = "<!-- Please add some values to this field repeater in demo post in order to see the fields here -->";
				    $arrParams[] = $this->createChildParam($key, null, array("raw_insert_text"=>$strText));
					
					continue;
				}
				
				//simple param
				
				$arrParams[] = $this->createChildParam($key);
			}
			
			
		}else{	//regular custom fields
			
			//should be $arrMetaKeys from pods
			
			if(empty($arrMetaKeys))
				$arrMetaKeys = UniteFunctionsWPUC::getPostMetaKeys($postID, "cf_");
							
			$title = "custom field";
			
			if(empty($arrMetaKeys))
				return($arrParams);
			
			$firstKey = $arrMetaKeys[0];
				
			foreach($arrMetaKeys as $key)
				$arrParams[] = $this->createChildParam($key);
			
		}
		
		
		//add functions
		$arrParams[] = $this->createChildParam("$title example with default",null,array("raw_insert_text"=>"{{ [param_prefix].$firstKey|default('default text') }}"));

		
		return($arrParams);
	}
	
	/**
	 * get image param add fields
	 */
	protected function getImageAddFields(){
		
		$arrFields = array();
		$arrFields[] = "title";
		$arrFields[] = "alt";
		$arrFields[] = "description";
		$arrFields[] = "caption";
		
		return($arrFields);
	}
	
	
	/**
	 * get thumb sizes
	 */
	protected function getThumbSizes(){
		
		$arrThumbSizes = UniteFunctionsWPUC::getArrThumbSizes();
		
		//modify sizes
		$arrSizesModified = array();
		
		foreach($arrThumbSizes as $key => $size){
			
			if($key == "medium")
				continue;
				
			$key = str_replace("-", "_", $key);
			
			$arrSizesModified[$key] = $size;
		}
		
		return($arrSizesModified);
	}
	
	
	
}