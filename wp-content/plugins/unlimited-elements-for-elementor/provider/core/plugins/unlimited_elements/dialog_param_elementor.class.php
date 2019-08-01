<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorDialogParamElementor extends UniteCreatorDialogParam{

	/**
	 * put elementor typography param field
	 */
	protected function putTypographyParamField(){
		?>
		
		<!-- selector 1 -->
		
		<div class="unite-inputs-label">
			
			<?php esc_html_e("CSS Selector", "unlimited_elements")?>:
		</div>		
		
		<input type="text" name="selector1" value="">
		
		<!-- selector 2 -->
		
		<div class="unite-inputs-sap"></div>
						
		<div class="unite-inputs-label">
			
			<?php esc_html_e("CSS Selector 2 (optional)", "unlimited_elements")?>:
		</div>		
		
		<input type="text" name="selector2" value="">
		
		<!-- selector 3 -->
		
		<div class="unite-inputs-sap"></div>
		
		<div class="unite-inputs-label">
			
			<?php esc_html_e("CSS Selector 3 (optional)", "unlimited_elements")?>:
		</div>		
		
		<input type="text" name="selector3" value="">
		
		<div class="unite-dialog-description-right">
			* <?php esc_html_e("The selector that the typography field will be related to. Can be related to several html tags.", "unlimited_elements")?>
		</div>
		
		<?php 
	}
	
	/**
	 * put param content
	 */
	protected function putParamFields($paramType){
	
		switch($paramType){
			
			case self::PARAM_TYPOGRAPHY:
				$this->putTypographyParamField();
			break;
			default:
				parent::putParamFields($paramType);
			break;
		}
	
	}
	
	
	/**
	 * filter elementor params
	 */
	private function filterMainParamsElementor($arrParams){
		
		$arrParamsOutput = array();
		foreach($arrParams as $paramType){
			
			//exclude shape
			switch($paramType){
				case self::PARAM_SHAPE:
					if(GlobalsUC::$inDev == false)
						continue(2);
				break;
				case self::PARAM_ADDONPICKER:
					continue(2);
				break;
			}
			
			$arrParamsOutput[] = $paramType;
		}
		
		//add typography
		$arrParamsOutput[] = self::PARAM_TYPOGRAPHY;
		
		return($arrParamsOutput);
	}
	
	
	/**
	 * filter main params
	 */
	protected function filterMainParams($arrParams){
		
		switch($this->addonType){
			case GlobalsUnlimitedElements::ADDONSTYPE_ELEMENTOR:
				$arrParams = $this->filterMainParamsElementor($arrParams);
			break;
		}
		
		return($arrParams);
	}
	
		/**
	 * init by addon type
	 * function for override
	 */
	protected function initByAddonType($addonType){
		
		if($addonType != GlobalsUnlimitedElements::ADDONSTYPE_ELEMENTOR)
			return(false);
		
		$this->option_putAdminLabel = false;
				
	}
	
	
}