<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');


class UniteCreatorAddonViewChildParams{
	
	
	/**
	 * create child param
	 */
	protected function createChildParam($param, $type = null, $addParams = false){
		
		$arr = array("name"=>$param, "type"=>$type);
		
		switch($type){
			case UniteCreatorDialogParam::PARAM_IMAGE:
				$arr["add_thumb"] = true;
				$arr["add_thumb_large"] = true;
			break;
		}
		
		if(!empty($addParams))
			$arr = array_merge($arr, $addParams);
		
		return($arr);
	}

	
	/**
	 * create child param
	 */
	protected function createChildParam_code($key, $text){
		
	    $arr = $this->createChildParam($key,
	    	null,
	    	array("raw_insert_text" => $text, 
	    	"rawvisual"=>true));		
		
		return($arr);
	}
	
	
	/**
	 * get post child params
	 */
	public function getChildParams_codeExamples(){
		
		$arrParams = array();
		
		//----- show data --------
		
		$key = "showData()";
		$text = "
{# This function will show all data in that you can use #} \n 
{{showData()}}
";
		
		$arrParams[] = $this->createChildParam_code($key, $text);
		
		//---- show debug -----
		
		$key = "showDebug()";
		$text = "{# This function show you some debug (with post list for example) #} \n 
					{{showDebug()}}";
		
		//------ if empty ------
		
		$key = "IF Empty";
		$text = "
{% if some_attribute is empty %}
	<!-- put your empty case html -->   
{% else %} 
	<!-- put your not empty html here -->   
{% endif %}	
";
		
		$arrParams[] = $this->createChildParam_code($key, $text);
		
		//----- simple if ------
		
		$key = "IF";
		$text = "
{% if some_attribute == \"some_value\" %}
	<!-- put your html here -->   
{% endif %}	
";
		$arrParams[] = $this->createChildParam_code($key, $text);
		
		
		//----- if else ------
		
		$key = "IF - Else";
		$text = "
{% if product_stock == 0 %}
	<!-- not available html -->   
{% else %}
	<!-- available html -->
{% endif %}
";
		$arrParams[] = $this->createChildParam_code($key, $text);
		
		
		//----- complex if ------
		
		$key = "IF - Else - Elseif";
		$text = "
{% if product_stock == 0 %}
	<!-- put your 0 html here -->   
{% elseif product_stock > 0 and product_stock < 20 %}
	<!-- put your 0-20 html here -->   
{% elseif product_stock >= 20 %}
	<!-- put your >20 html here -->   
{% endif %}	
";

		$arrParams[] = $this->createChildParam_code($key, $text);


		//----- for in (loop) ------
		
		$key = "For In (loop)";
		$text = "
{% for product in woo_products %}
	
	<!-- use attributes inside the product, works if the product is array -->   
	<span> {{ product.title }} </span>
	<span> {{ product.price }} </span>
	
{% endif %}	
";
		
		$arrParams[] = $this->createChildParam_code($key, $text);
		
		//----- for in (loop) ------
		
		$key = "HTML Output - |raw";
		$text = "
{# use the raw filter for printing attribute with html tags#}
{{ some_attribute|raw }}
";
		
		$arrParams[] = $this->createChildParam_code($key, $text);
		
		//----- default value ------
		
		$key = "Default Value";
		$text = "
{# use the default value filter in case that no default value provided (like in acf fields) #}
{{ cf_attribute|default('text in case that not defined') }}
";

		$arrParams[] = $this->createChildParam_code($key, $text);
		
		
		return($arrParams);
	}
	

	/**
	 * get post child params
	 */
	public function getChildParams_codeExamplesJS(){
		
		$arrParams = array();
		
		//----- show data --------
		
		$key = "jQuery(document).ready()";
		$text = " 
jQuery(document).ready(function(){

	/* put your code here, a must wrapper for every jQuery enabled widget */

});
		";
		$arrParams[] = $this->createChildParam_code($key, $text);
		
		
		return($arrParams);
	}
	
	
	
}