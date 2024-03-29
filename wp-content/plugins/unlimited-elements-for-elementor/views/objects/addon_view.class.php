<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

require_once 'addon_view_childparams.class.php';

class UniteCreatorAddonView{
	
	protected $objAddon;
	protected $settingsItemOutput,$objAddonType, $addonType;
	protected $showToolbar = true, $showHeader = true;
	
	//show defenitions
	protected $putAllTabs = true, $arrTabsToPut = array();
	protected $isSVG = false, $showContstantVars = true, $showPreviewSettings = true;
	protected $showAddonDefaluts = true, $showTestAddon = true;
	protected $textSingle, $textPlural, $tabHtmlTitle = null;
	protected $htmlEditorMode = null, $arrCustomConstants = null;
	protected $urlViewBack = null;
	protected $showSmallIconOption = null;
	protected $addonOptions;
	protected $arrSkipPanelParams = array();	//attributes that don't show in right panel
	protected $objChildParams = array();
	
	
	private function z______INIT_______(){}
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$this->objChildParams = new UniteCreatorAddonViewChildParams();
		
	}
	
	
	/**
	 * run view
	 */
	public function runView($isPutHtml = true){
		
		$this->init();
		
		$this->putHtml();
		
		if($isPutHtml == false)
			return(false);
		
	}
	
	/**
	 * validate init settings
	 */
	private function validateInitSettings(){
		
		if($this->putAllTabs == false && empty($this->arrTabsToPut))
			UniteFunctionsUC::throwError("if all tabs setting turned off should be some tabs in arrTabsToPut array");
			
	}
	
	
	/**
	 * get settings item output
	 */
	private function initSettingsItem(){
	    
		$options = $this->objAddon->getOptions();
		$paramsItems = $this->objAddon->getParamsItems();
		
		//items editor - settings
		$settingsItem = new UniteCreatorSettings();
		$settingsItem->addRadioBoolean("enable_items", esc_html__("Enable Items", "unlimited_elements"), false);
	
		$settingsItem->setStoredValues($options);
	
		$this->settingsItemOutput = new UniteSettingsOutputInlineUC();
		$this->settingsItemOutput->init($settingsItem);
		$this->settingsItemOutput->setAddCss("[wrapperid] .unite_table_settings_wide th{width:100px;}");
	
	}
	
	/**
	 * init svg addon type
	 */
	private function initByAddonType_svg(){
		
		$this->putAllTabs = false;
		$this->arrTabsToPut["html"] = true;
		$this->isSVG = true;
	}
	
	/**
	 * init by addon type generally
	 */
	private function initByAddonType_general(){
		
		if($this->objAddonType->addonView_htmlTabOnly == true){
			$this->putAllTabs = false;
			$this->arrTabsToPut["html"] = true;
		}
		
		if($this->objAddonType->addonView_showConstantVars == false)
			$this->showContstantVars = false;
				
		if($this->objAddonType->addonView_showPreviewSettings == false)
			$this->showPreviewSettings = false;
		
		if($this->objAddonType->addonView_showAddonDefaults == false)
			$this->showAddonDefaluts = false;
		
		if($this->objAddonType->addonView_showTestAddon == false)
			$this->showTestAddon = false;
			
		if(!empty($this->objAddonType->addonView_tabHtmlTitle))
			$this->tabHtmlTitle = $this->objAddonType->addonView_tabHtmlTitle;
			
		if(!empty($this->objAddonType->addonView_htmlEditorMode))
			$this->htmlEditorMode = $this->objAddonType->addonView_htmlEditorMode;
		
		if(!empty($this->objAddonType->addonView_arrCustomConstants))
			$this->arrCustomConstants = $this->objAddonType->addonView_arrCustomConstants;
		
		if(!empty($this->objAddonType->addonView_urlBack))
			$this->urlViewBack = $this->objAddonType->addonView_urlBack;
		
		$this->showSmallIconOption = $this->objAddonType->addonView_showSmallIconOption;
		
	}
	
	
	/**
	 * init by addon type
	 */
	private function initByAddonType(){
		
		$this->textSingle = $this->objAddonType->textSingle;
		$this->textPlural = $this->objAddonType->textPlural;
		
		if($this->objAddonType->isSVG){
			$this->initByAddonType_svg();
			return(false);
		}
		
		$this->initByAddonType_general();
	}
	
	/**
	 * 
	 * init skip panel params
	 */
	public function initSkipPanelParams(){
		
		$this->arrSkipPanelParams = array();
		$this->arrSkipPanelParams[UniteCreatorDialogParam::PARAM_TYPOGRAPHY] = true;
	}
	
	
	/**
	 * init the view
	 */
	private function init(){
		
		$addonID = UniteFunctionsUC::getGetVar("id","",UniteFunctionsUC::SANITIZE_ID);
				
		if(empty($addonID))
			UniteFunctionsUC::throwError("Addon ID not given");
		
		$this->objAddon = new UniteCreatorAddon();
		$this->objAddon->initByID($addonID);
		
		$this->addonType = $this->objAddon->getType();
		$this->objAddonType = $this->objAddon->getObjAddonType();
		
		$this->addonOptions = $this->objAddon->getOptions();
				
		UniteCreatorAdmin::setAdminGlobalsByAddonType($this->objAddonType, $this->objAddon);
		
		$this->initByAddonType();
		
		$this->initSettingsItem();
		
		$this->validateInitSettings();
		
		$this->initSkipPanelParams();
	}
	
	private function z________PUT_HTML______(){}
	
	
	/**
	 * get header title
	 */
	protected function getHeaderTitle(){
		
		$title = $this->objAddon->getTitle(true);
		$addonID = $this->objAddon->getID();
		
		$headerTitle = esc_html__("Edit ","unlimited_elements").$this->textSingle;
		$headerTitle .= " - " . $title;
		
		return($headerTitle);
	}
	
	
	/**
	 * put top html
	 */
	private function putHtml_top(){
		
		$headerTitle = $this->getHeaderTitle();
		
		require HelperUC::getPathTemplate("header");
	}
	
	/**
	 * modify general settings by svg type
	 */
	private function modifyGeneralSettings_SVG($generalSettings){
		
		$generalSettings->hideSetting("show_small_icon");
		$generalSettings->hideSetting("text_preview");
		
		return($generalSettings);
	}
	
	/**
	 * modify general settings by svg type
	 */
	private function modifyGeneralSettings_general($generalSettings){

		//hide preview settings
		if($this->showPreviewSettings == false){
			$generalSettings->hideSetting("show_small_icon");
			$generalSettings->hideSetting("text_preview");
			$generalSettings->hideSetting("preview_size");
			$generalSettings->hideSetting("preview_bgcol");
		}
		
		if($this->showSmallIconOption == false){
			$generalSettings->hideSetting("show_small_icon");
			
		}
		
		return($generalSettings);
	}
	
	
	/**
	 * init general settings from file
	 */
	private function initGeneralSettings(){

		$filepathAddonSettings = GlobalsUC::$pathSettings."addon_fields.php";
		
		require $filepathAddonSettings;
		
		if($this->isSVG)
			$generalSettings = $this->modifyGeneralSettings_SVG($generalSettings);
		else
			$generalSettings = $this->modifyGeneralSettings_general($generalSettings);
		
			
		return($generalSettings);
	}
	
	
	/**
	 * put general settings tab html
	 */
	private function putHtml_generalSettings(){
		
		$addonID = $this->objAddon->getID();
		$title = $this->objAddon->getTitle(true);
		
		$name = $this->objAddon->getNameByType();
		
		$generalSettings = $this->initGeneralSettings();
		
		//set options from addon
		$arrOptions = $this->objAddon->getOptions();
		$generalSettings->setStoredValues($arrOptions);
		
		$settingsOutput = new UniteSettingsOutputWideUC();
		$settingsOutput->init($generalSettings);
		
		$addonTypeTitle = $this->objAddonType->textShowType;
		
		
		?>
		
		<div class="uc-edit-addon-col uc-col-first">
		
			<span id="addon_id" data-addonid="<?php echo esc_attr($addonID)?>" style="display:none"></span>
			
			<?php echo $this->textSingle.esc_html__(" Title", "unlimited_elements"); ?>:
			
			<div class="vert_sap5"></div>
			
			<input type="text" id="text_addon_title" value="<?php echo esc_attr($title)?>" class="unite-input-regular">
			
			<!-- NAME -->
			
			<div class="vert_sap15"></div>
			
			<?php echo $this->textSingle.esc_html__(" Name", "unlimited_elements"); ?>:
			
			<div class="vert_sap5"></div>
			
			<input type="text" id="text_addon_name" value="<?php echo esc_attr($name)?>" class="unite-input-regular">
			
			
			<!-- TYPE -->
			<div class="vert_sap15"></div>
			
			<?php esc_html_e("Addon Type", "unlimited_elements");?>: <b> <?php echo esc_html($addonTypeTitle)?> </b>
			
			
			<?php UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_EDIT_ADDON_ADDSETTINGS, $arrOptions)?>
			
		</div>
		
		<div class="uc-edit-addon-col uc-col-second">
				<?php 
					$settingsOutput->draw("uc_general_settings", true); 
				?>
		</div>
		
		
		<div class="unite-clear"></div>
		
		<div class="vert_sap15"></div>
		
		
		<?php
		
	}
	
	/**
	 * if put tab
	 */
	private function isPutTab($tabName){
		
		if($this->putAllTabs == true)
			return(true);
		
		if(isset($this->arrTabsToPut[$tabName]))
			return(true);
		
		return(false);
	}
	
	
	/**
	 * put tabs html
	 */
	private function putHtml_tabs(){
		
		$isPut_general = true;		//always put general tab
		
		$isPut_html = $this->isPutTab("html");
		$isPut_attr = $this->isPutTab("attr");
		$isPut_itemattr = $this->isPutTab("itemattr");
		$isPut_css = $this->isPutTab("css");
		$isPut_js = $this->isPutTab("js");
		$isPut_includes = $this->isPutTab("includes");
		$isPut_assets = $this->isPutTab("assets");
		
		$htmlTabTitle = esc_html__("HTML","unlimited_elements");
		if($this->isSVG == true)
			$htmlTabTitle = esc_html__("SVG Content","unlimited_elements");
		else{
			
			if(!empty($this->tabHtmlTitle))
				$htmlTabTitle = $this->tabHtmlTitle;
			
		}
		
		?>
		
		<div id="uc_tabs" class="uc-tabs" data-inittab="uc_tablink_general">
			
			<?php if($isPut_general):?>
			<a id="uc_tablink_general" href="javascript:void(0)" data-contentid="uc_tab_general">
				<?php esc_html_e("General", "unlimited_elements")?> 
			</a>
			<?php endif?>
			
			<?php if($isPut_attr):?>
			<a id="uc_tablink_attr" href="javascript:void(0)" data-contentid="uc_tab_attr">
				<?php esc_html_e("Attributes", "unlimited_elements")?> 
			</a>
			<?php endif?>
			
			<?php if($isPut_itemattr):?>
			<a id="uc_tablink_itemattr" href="javascript:void(0)" data-contentid="uc_tab_itemattr">
				<?php esc_html_e("Item Attributes", "unlimited_elements")?> 
			</a>
			<?php endif?>
			
			<?php if($isPut_html):?>
			<a id="uc_tablink_html" href="javascript:void(0)" data-contentid="uc_tab_html">
				<?php echo esc_html($htmlTabTitle)?>
			</a>
			<?php endif?>
			
			<?php if($isPut_css):?>
			<a id="uc_tablink_css" href="javascript:void(0)" data-contentid="uc_tab_css">
				<?php esc_html_e("CSS", "unlimited_elements")?>
			</a>
			<?php endif?>
			
			<?php if($isPut_js):?>
			<a id="uc_tablink_js" href="javascript:void(0)" data-contentid="uc_tab_js">
				<?php esc_html_e("Javascript", "unlimited_elements")?>
			</a>
			<?php endif?>
			
			<?php if($isPut_includes):?>
			<a id="uc_tablink_includes" href="javascript:void(0)" data-contentid="uc_tab_includes">
				<?php esc_html_e("js/css Includes", "unlimited_elements")?>
			</a>
			<?php endif?>
			
			<?php if($isPut_assets):?>
			<a id="uc_tablink_assets" href="javascript:void(0)" data-contentid="uc_tab_assets">
				<?php esc_html_e("Assets", "unlimited_elements")?>
			</a>
			<?php endif?>
			
		</div>
		
		<div class="unite-clear"></div>
		
		<?php 
	}
	
	
	/**
	 * put item for library include
	 */
	private function putIncludeLibraryItem($title, $name, $arrIncludes){
	
		$htmlChecked = "";
		if(in_array($name, $arrIncludes) == true)
			$htmlChecked = "checked='checked'";
	
		?>
		
			<li>
				<input type="checkbox" id="check_include_<?php echo esc_attr($name)?>" data-include="<?php echo esc_attr($name)?>" <?php echo UniteProviderFunctionsUC::escAddParam($htmlChecked)?>>
			
				<label for="check_include_<?php echo esc_attr($name)?>">
					<?php echo esc_html($title)?>
				</label>
								
			</li>
		
		<?php 
	}

	
	/**
	 * put library includes
	 */
	private function putHtml_LibraryIncludes($arrJsLibIncludes){
		
		$objLibrary = new UniteCreatorLibrary();
		$arrLibrary = $objLibrary->getArrLibrary();
				
		foreach($arrLibrary as $item){
			$name = $item["name"];
			$title = $item["title"];
			
			$this->putIncludeLibraryItem($title, $name, $arrJsLibIncludes);
		}
		
			
	}
	
	/**
	 * put includes assets browser
	 */
	private function putHtml_Includes_assetsBrowser(){
		
		$objAssets = new UniteCreatorAssetsWork();
		$objAssets->initByKey("includes", $this->objAddon);
		
		$pathAssets = $this->objAddon->getPathAssetsFull();
		$objAssets->putHTML($pathAssets);
		
	}
	
	
	/**
	 * put includes html
	 */
	private function putHtml_Includes(){
		
		$arrJsLibIncludes = $this->objAddon->getJSLibIncludes();
		$arrJsIncludes = $this->objAddon->getJSIncludes();
		$arrCssIncludes = $this->objAddon->getCSSIncludes();
		
		$dataJs = UniteFunctionsUC::jsonEncodeForHtmlData($arrJsIncludes, "init");
		$dataCss = UniteFunctionsUC::jsonEncodeForHtmlData($arrCssIncludes, "init");
		
		
		?>
			<table id="uc_table_includes" class="unite_table_items">
				<thead>
					<tr>
						<th class="uc-table-includes-left">
							<b>
							<?php esc_html_e("Choose From Browser", "unlimited_elements")?>
							</b>
						</th>
						<th class="uc-table-includes-right">
							<b>
							<?php esc_html_e("JS / Css Includes", "unlimited_elements")?>
							</b>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td valign="top">
							<?php $this->putHtml_Includes_assetsBrowser(); ?>
						</td>
						<td valign="top">
							
							<ul id="uc-js-libraries" class="unite-list-hor">
								<li class="pright_10">
									<span class="unite-title2"><?php esc_html_e("Libraries", "unlimited_elements")?>:</span> </b>
								</li>
								<?php $this->putHtml_LibraryIncludes($arrJsLibIncludes)?>
							</ul>
							
							<div class="unite-clear"></div>
							
							<div id="uc_includes_wrapper">
								
								<div class="unite-title2">Js Includes:</div>
								
								<ul id="uc-js-includes" class="uc-js-includes" data-type="js" <?php echo UniteProviderFunctionsUC::escAddParam($dataJs)?>></ul>
								
								<div class="unite-title2">Css Includes:</div>
								
								<ul id="uc-css-includes" class="uc-css-includes" data-type="css" <?php echo UniteProviderFunctionsUC::escAddParam($dataCss)?>></ul>
							
							</div>
							
						</td>
					</tr>
				</tbody>
			</table>
			
			<div id="uc_dialog_unclude_settings" title="<?php esc_html_e("Include Settings")?>" class="unite-inputs" style="display:none">
				<div class="unite-dialog-inside">
				
					<?php esc_html_e("Include When:", "unlimited_elements")?>
					
					<span class="hor_sap"></span>
					
					<select id="uc_dialog_include_attr"></select>
					
					<span id="uc_dialog_include_value_container" style="display:none">
					
						<span class="hor_sap5"></span>
						
						<?php esc_html_e("equals", "unlimited_elements")?>
						
						<span class="hor_sap5"></span>
						
						<select id="uc_dialog_include_values"></select>
						
					</span>
					
					<?php HelperHtmlUC::putDialogControlFieldsNotice() ?>
				</div>
			</div>
			
						
			<?php 
			
	}
	
	
	/**
	 * put assets tab html
	 */
	private function putHtml_assetsTab(){
		
		$path = $this->objAddon->getPathAssets();
		$pathAbsolute = $this->objAddon->getPathAssetsFull();
		
		$textNotSet = esc_html__("[not set]", "unlimited_elements");
		
		$unsetAddHtml = "style='display:none'";
		$htmlPath = $textNotSet;
		$dataPath = "";
		if(!empty($path)){
			$unsetAddHtml = "";
			$htmlPath = htmlspecialchars($path);
			$dataPath = $htmlPath;
		}
		
		?>
			<div class="uc-assets-folder-wrapper">
				<span class="uc-assets-folder-label"><?php echo $this->textSingle.esc_html__(" Assets Path: ", "unlimited_elements")?></span>
				<span id="uc_assets_path" class="uc-assets-folder-folder" data-path="<?php echo esc_attr($dataPath)?>" data-textnotset="<?php echo esc_attr($textNotSet)?>"><?php echo esc_html($htmlPath)?></span>
				<a id="uc_button_set_assets_folder" href="javascript:void(0)" class="unite-button-secondary"><?php esc_html_e("Set", "unlimited_elements")?></a>
				<a id="uc_button_set_assets_unset" href="javascript:void(0)" class="unite-button-secondary" <?php echo UniteProviderFunctionsUC::escAddParam($unsetAddHtml)?>><?php esc_html_e("Unset", "unlimited_elements")?></a>
			</div>
		<?php 
		
		$objAssets = new UniteCreatorAssetsWork();
		$objAssets->initByKey("assets_manager", $this->objAddon);
				
		$objAssets->putHTML($pathAbsolute);
	}
	
	
	/**
	 * put expand link
	 */
	private function putLinkExpand(){
		?>
			<a class="uc-tabcontent-link-expand" href="javascript:void(0)"><?php esc_html_e("expand", "unlimited_elements");?></a>
		<?php 
	}
	
	
	/**
	 * put html tab content
	 */
	private function putHtml_tabTableRow($textareaID, $title, $areaHtml, $paramsPanelID, $addVariableID = null, $isItemsRelated = false, $params = array()){
		
		$rowClass = "";
		$rowAddHtml = "";
				
		$paramsPanelClassAdd = " uc-params-panel-main";
		
		if($isItemsRelated == true){
			$rowClass = "uc-items-related";
			$hasItems = $this->objAddon->isHasItems();
			
			if($hasItems == false)
				$rowAddHtml = "style='display:none'";
			
			$paramsPanelClassAdd = "";
			
		}
		
		$isExpanded = UniteFunctionsUC::getVal($params, "expanded");
		$isExpanded = UniteFunctionsUC::strToBool($isExpanded);
		
		$mode = UniteFunctionsUC::getVal($params, "mode");
		
		$areaAddParams = "";
		if(!empty($mode))
			$areaAddParams = " data-mode='{$mode}'";
		
		
		if($isExpanded == true)
			$rowClass .= " uc-row-expanded";
		
		if(!empty($rowClass)){
			$rowClass = esc_attr($rowClass);
			$rowClass = "class='$rowClass'";
		}
		
		$styleRight = "";
		if($this->isSVG == true)
			$styleRight = 'style="display:none;"';
		
		?>
					<tr <?php echo UniteProviderFunctionsUC::escAddParam($rowClass)?> <?php echo UniteProviderFunctionsUC::escAddParam($rowAddHtml)?>>
						<td class="uc-tabcontent-cell-left">
						
							<div class="uc-editor-title"><?php echo esc_html($title)?></div>
							<textarea id="<?php echo esc_attr($textareaID)?>" class="area_addon <?php echo esc_attr($textareaID)?>" <?php echo UniteProviderFunctionsUC::escAddParam($areaAddParams)?>><?php echo esc_html($areaHtml)?></textarea>
							<?php if($isExpanded == false)
									$this->putLinkExpand()?>
						</td>
						<td class="uc-tabcontent-cell-right" <?php echo UniteProviderFunctionsUC::escAddParam($styleRight)?>>

							<?php if($isItemsRelated == true):?>
								<div class="uc-params-panel-filters">
									<a href="javascript:void(0)" class="uc-filter-active" data-filter="item" onfocus="this.blur()"><?php esc_html_e("Item", "unlimited_elements")?></a>
									<a href="javascript:void(0)" data-filter="main" onfocus="this.blur()"><?php esc_html_e("Main", "unlimited_elements")?></a>
								</div>
							<?php endif?>
						
							<div id="<?php echo esc_attr($paramsPanelID)?>" class="uc-params-panel<?php echo esc_attr($paramsPanelClassAdd)?>"></div>
							
							<?php if(!empty($addVariableID)):?>
						    <a id="<?php echo esc_attr($addVariableID)?>" type="button" href="javascript:void(0)" class="unite-button-secondary mleft_20"><?php esc_html_e("Add Variable", "unlimited_elements")?></a>
							<?php endif?>
							
						</td>
					</tr>
		
		<?php 
	}
	
	
	/**
	 * put tab table sap
	 */
	private function putHtml_tabTableSap($isItemsRelated = false){
		
		$rowClass = "";
		if($isItemsRelated == true)
			$rowClass = "class='uc-items-related'";
		
		?>
			<tr <?php echo UniteProviderFunctionsUC::escAddParam($rowClass)?>>
				<td colspan="2"><div class="vert_sap10"></div></td>
			</tr>
		<?php 
	}
	
	
	/**
	 * put overwiew tab html
	 */
	private function putHtml_overviewTab(){
		
		$title = $this->objAddon->getTitle();
		$name = $this->objAddon->getName();
		$description = $this->objAddon->getDescription();
		$link = $this->objAddon->getOption("link_resource");
		if(!empty($link))
			$link = HelperHtmlUC::getHtmlLink($link, $link, "uc_overview_link","",true);
		
		$addonIcon = $this->objAddon->getUrlIcon();
		
		
		?>
		<div class="uc-tab-overview">
			<div class="uc-section-inline"><?php esc_html_e("Widget Title", "unlimited_elements")?>: <span id="uc_overview_title" class="unite-bold"><?php echo esc_html($title)?></span></div>
			<div class="uc-section-inline"><?php esc_html_e("Widget Name", "unlimited_elements")?>: <span id="uc_overview_name" class="unite-bold"><?php echo esc_html($name)?></span></div>
			<div class="uc-section">
				<div class="uc-section-title"><?php esc_html_e("Addon Description", "unlimited_elements")?>:</div>
				<div id="uc_overview_description" class="uc-section-content uc-desc-wrapper">
					<?php echo esc_html($description)?>
				</div>
				<div class="unite-clear"></div>
			</div>
			<div class="uc-section-inline"><?php esc_html_e("Link to resource", "unlimited_elements")?>: <?php echo esc_html($link)?></div>
			<div class="uc-section">
				<div class="uc-section-title uc-title-icon"><?php esc_html_e("Widget Icon", "unlimited_elements")?>:</div>
				<div id="uc_overview_icon" class="uc-section-content uc-addon-icon-small" style="background-image:url('<?php echo UniteProviderFunctionsUC::escAddParam($addonIcon)?>')"></div> 
			</div>
			
		</div>
		
		
		<?php
	}
	
	
	/**
	 * put powered by twig html
	 */
	private function putHTMLPoweredByTwig(){
		?>
		 <div class="uc-edit-addon-poweredby">
		 
		 	<?php _e("Powered by Twig Template Engine. ", "unlimited_elements")?> 
		 		<a href="<?php echo GlobalsUC::LINK_TWIG?>" target="_blank"><?php _e("show documentation", "unlimited_elements")?></a>.
		 		<?php _e("To show code examples in right panel ")?> 
		 		
		 		<a  href="javascript:void(0)" class="uc-link-code-examples" ><?php _e("click here", "unlimited_elements")?></a>.
		 		
		 </div>
		<?php 
	}
	
	/**
	 * put tabs content
	 */
	private function putHtml_content(){
		
		$css = $this->objAddon->getCss(true);
		$cssItem = $this->objAddon->getCssItem(true);
		
		$html = $this->objAddon->getHtml(true);
		$htmlItem = $this->objAddon->getHtmlItem(true);
		$htmlItem2 = $this->objAddon->getHtmlItem2(true);
		
		$js = $this->objAddon->getJs(true);
		$hasItems = $this->objAddon->isHasItems();
		
		$params = $this->objAddon->getParams();
		$paramsItems = $this->objAddon->getParamsItems();
		
		$paramsEditorItems = new UniteCreatorParamsEditor();
		
		if($hasItems == false)
			$paramsEditorItems->setHiddenAtStart();
		
		$paramsEditorItems->init("items");
		
				
		?>
		
		<div id="uc_tab_contents" class="uc-tabs-content-wrapper uc-addon-props">
			
			<!-- General -->
			
			<div id="uc_tab_general" class="uc-tab-content" style="display:none">
				
				<?php 
				try{
					
					$this->putHtml_generalSettings();
					
				}catch(Exception $e){
					HelperHtmlUC::outputException($e);
				}
				?>
					
			</div>
			
			<!-- Attributes -->
			
			<div id="uc_tab_attr" class="uc-tab-content" style="display:none">
					
				<?php 
					$paramsEditorMain = new UniteCreatorParamsEditor();
					$paramsEditorMain->init("main");
					$paramsEditorMain->outputHtmlTable();
				?>
				
			</div>
			
			<!-- Item Attributes -->
			
			<div id="uc_tab_itemattr" class="uc-tab-content uc-tab-itemattr" style="display:none">
			
				<?php 
					$this->settingsItemOutput->draw("uc_form_edit_addon");
					$paramsEditorItems->outputHtmlTable();
				?>
			
			</div>
			
			
			<!-- HTML -->
		
			<div id="uc_tab_html" class="uc-tab-content" style="display:none">
						
				<table class="uc-tabcontent-table">
					
					<?php 
						
						//------------- put html row
					
						$textareaID = "area_addon_html";
						$rowTitle = $this->textSingle.esc_html__(" HTML","unlimited_elements");
						
						if($this->isSVG == true)
							$rowTitle = esc_html__("SVG Content","unlimited_elements");
						
						if(!empty($this->tabHtmlTitle))
							$rowTitle = $this->tabHtmlTitle.esc_html__(" Content","unlimited_elements");
						
							
						$areaHtml = $html;
						$paramsPanelID = "uc_params_panel_main";
						$addVariableID = "uc_params_panel_main_addvar";
						
						$params = array();
						if(!empty($this->htmlEditorMode))
							$params["mode"] = $this->htmlEditorMode;
						
						$params["expanded"] = true;
						
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, $addVariableID, false, $params);
						
						
						//------------- put html item row
						
						$this->putHtml_tabTableSap(true);
						
						$textareaID = "area_addon_html_item";
						$rowTitle = esc_html__("Item HTML","unlimited_elements");
						$areaHtml = $htmlItem;
						$paramsPanelID = "uc_params_panel_item";
						$addVariableID = "uc_params_panel_item_addvar";
						$isItemsRelated = true;
												
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, $addVariableID, $isItemsRelated);

						$this->putHtml_tabTableSap(true);
						
						//------------- put html item row 2
						
						$textareaID = "area_addon_html_item2";
						$rowTitle = esc_html__("Item HTML 2","unlimited_elements");
						$areaHtml = $htmlItem2;
						$paramsPanelID = "uc_params_panel_item2";
						$addVariableID = "uc_params_panel_item_addvar2";
						$isItemsRelated = true;
						
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, $addVariableID, $isItemsRelated);
						
					?>				
					
				</table>
				
				<?php $this->putHTMLPoweredByTwig() ?>
			</div>
			
			<!-- CSS -->
			
			<div id="uc_tab_css" class="uc-tab-content" style="display:none">
			
				<table class="uc-tabcontent-table">
				
					<?php 
						//--------- css addon --------
					
						$textareaID = "area_addon_css";
						$rowTitle = $this->textSingle.esc_html__(" CSS","unlimited_elements");
						$areaHtml = $css;
						$paramsPanelID = "uc_params_panel_css";
						
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, null);
						
						//--------- css item --------
						
						$textareaID = "area_addon_css_item";
						$rowTitle = esc_html__("Item CSS","unlimited_elements");
						$areaHtml = $cssItem;
						$paramsPanelID = "uc_params_panel_css_item";
						
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, null, true);
						
					?>
					
				</table>
				
				<?php $this->putHTMLPoweredByTwig() ?>
			
			</div>
			
			<!-- JS -->
			
			<div id="uc_tab_js" class="uc-tab-content" style="display:none">
				
				<table class="uc-tabcontent-table">
					<?php 
					$textareaID = "area_addon_js";
					$rowTitle = $this->textSingle.esc_html__(" Javascript","unlimited_elements");
					$areaHtml = $js;
					$paramsPanelID = "uc_params_panel_js";
					$params = array();
					$params["expanded"] = true;
					
					$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, null, false, $params);
					
					?>
				
				</table>
				
				<?php $this->putHTMLPoweredByTwig() ?>
				
			</div>
			
			<!-- INCLUDES -->
			<div id="uc_tab_includes" class="uc-tab-content" style="display:none">
				
				<?php $this->putHtml_Includes()?>
				
			</div>
	
			<div id="uc_tab_assets" class="uc-tab-content" style="display:none">
				
				<?php $this->putHtml_assetsTab() ?>
				
			</div>
			
		</div>
		
		<!-- END TABS -->
		
		
		<?php 
	}

	
	/**
	 * put action buttons html
	 */
	private function putHtml_actionButtons(){
		
		$addonID = $this->objAddon->getID();
		$addonType = $this->objAddon->getType();
		
		$urlTestAddon = HelperUC::getViewUrl_TestAddon($addonID);
		
		$urlPreviewAddon = HelperUC::getViewUrl_TestAddon($addonID,"preview=1");
		
		$urlAddonDefaults = helperuc::getViewUrl_AddonDefaults($addonID);
		
		$textPreviewAddon = esc_html__("Preview ","unlimited_elements").$this->textSingle;
		$textTestAddon = esc_html__("Test ","unlimited_elements").$this->textSingle;
		$textBack = esc_html__("Back To ","unlimited_elements").$this->textPlural.esc_html__(" List","unlimited_elements");
		$textDefaults = $this->textSingle.esc_html__(" Defaults","unlimited_elements");
		
		$textExport = esc_html__("Export ", "unlimited_elements").$this->textSingle;
		
		$isExistsInCatalog = $this->objAddon->isExistsInCatalog();
		
		$urlBack = HelperUC::getViewUrl_Addons($addonType);
		
		if(!empty($this->urlViewBack))
			$urlBack = $this->urlViewBack;
		
			
		?>
		
		<div class="uc-edit-addon-buttons-panel-wrapper">
		
			<div id="uc_buttons_panel" class="uc-edit-addon-buttons-panel">
			
				<div class="unite-float-left">
				
					<div class="uc-button-action-wrapper">
						<a id="button_update_addon" class="button_update_addon unite-button-primary" href="javascript:void(0)"><?php esc_html_e("Update", "unlimited_elements");?></a>
						
						<div style="padding-top:6px;">
							
							<span id="uc_loader_update" class="loader_text" style="display:none"><?php esc_html_e("Updating...", "unlimited_elements")?></span>
							<span id="uc_message_addon_updated" class="unite-color-green" style="display:none"></span>
							
						</div>
					</div>
					
					<a class="unite-button-secondary" href="<?php echo esc_attr($urlBack)?>"><?php echo esc_html($textBack)?></a>
										
					<?php if($this->showAddonDefaluts == true):?>
					<a href="<?php echo esc_attr($urlAddonDefaults)?>" class="unite-button-secondary"><?php echo esc_html($textDefaults) ?></a>
					<?php endif?>
					
					<?php if($this->showTestAddon == true):?>
					<a href="<?php echo esc_attr($urlTestAddon)?>" class="unite-button-secondary " ><?php echo esc_html($textTestAddon)?></a>
					
					<a href="<?php echo esc_attr($urlPreviewAddon)?>" class="unite-button-secondary " ><?php echo esc_html($textPreviewAddon)?></a>
					<?php endif?>

					<?php if($isExistsInCatalog == true): ?>
					
						<a id="uc_button_update_catalog" class="button_update_addon unite-button-secondary" href="javascript:void(0)"><?php esc_html_e("Update From Catalog", "unlimited_elements");?></a>
						<span id="uc_loader_update_catalog" class="loader_text" style="display:none"><?php esc_html__("Updating...", "unlimited_elements"); ?></span>
						<span id="uc_message_addon_updated_catalog" class="unite-color-green" style="display:none"></span>
					
					<?php endif?>
					
				</div>
				
				<div class="unite-float-right mright_10">
					<a id="button_export_addon" href="javascript:void(0)" class="unite-button-secondary " ><?php echo esc_html($textExport)?></a>
				</div>
				
				
				<div class="unite-clear"></div>
							
			</div>
		</div>
		<?php 
	}
	
	private function z__________PARAMS_______(){}
	
	
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
	 * add custom fields
	 * function for override
	 */
	protected function addCustomFieldsParams($arrParams, $postID){
		
		return($arrParams);
	}
	
	
	/**
	 * add taxonomies params
	 * function for override
	 */
	protected function addTaxonomiesParams($arrParams, $postID){
		
		return($arrParams);
	}
	
	/**
	 * add post category params
	 */
	protected function addPostCategoryParams($arrParams){
		
		$arrParams[] = $this->createChildParam("category_id");
		$arrParams[] = $this->createChildParam("category_name");
		$arrParams[] = $this->createChildParam("category_slug");
		
		return($arrParams);
	}
	
	
	/**
	 * get post child params
	 */
	public function getChildParams_post($postID = null, $arrAdditions = array()){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("id");
		$arrParams[] = $this->createChildParam("title",UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("alias");
		$arrParams[] = $this->createChildParam("content", UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("intro", UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("link");
		$arrParams[] = $this->createChildParam("image", UniteCreatorDialogParam::PARAM_IMAGE);
		$arrParams[] = $this->createChildParam("date",null,array("raw_insert_text"=>"{{[param_name]|date(\"d F Y, H:i\")}}"));
		$arrParams[] = $this->createChildParam("postdate",null,array("raw_insert_text"=>"{{putPostDate([param_prefix].id,\"d F Y, H:i\")}}"));
		$arrParams[] = $this->createChildParam("tagslist",null,array("raw_insert_text"=>"{{putPostTags([param_prefix].id)}}"));
		
		
		//add post additions
		if(empty($arrAdditions))
			return($arrParams);
				
		foreach($arrAdditions as $addition){
			
			switch($addition){
				case GlobalsProviderUC::POST_ADDITION_CATEGORY:
					
					$arrParams = $this->addPostCategoryParams($arrParams);
					
				break;
				case GlobalsProviderUC::POST_ADDITION_CUSTOMFIELDS:
					
					if(!empty($postID))
						$arrParams = $this->addCustomFieldsParams($arrParams, $postID);
					
				break;
			}
		}
			
		
		return($arrParams);
	}
	
		
	/**
	 * get post child params
	 */
	protected function getChildParams_instagramItem(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("caption",UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("thumb");
		$arrParams[] = $this->createChildParam("image");
		$arrParams[] = $this->createChildParam("link");
		$arrParams[] = $this->createChildParam("num_likes");
		$arrParams[] = $this->createChildParam("num_comments");
		$arrParams[] = $this->createChildParam("time_passed");
		$arrParams[] = $this->createChildParam("link");
		$arrParams[] = $this->createChildParam("isvideo");
		$arrParams[] = $this->createChildParam("num_video_views");
		
		return($arrParams);
	}
	
	
	/**
	 * get post child params
	 */
	protected function getAddParams_form(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("start",UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("end",UniteCreatorDialogParam::PARAM_EDITOR);
		
		return($arrParams);
	}
	
	
	/**
	 * add param for form item
	 */
	protected function getAddParams_formItem(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("form_field",UniteCreatorDialogParam::PARAM_EDITOR);
		
		return($arrParams);
	}
	
	/**
	 * get post child params
	 */
	protected function getChildParams_instagramMain(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("name", UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("username");
		$arrParams[] = $this->createChildParam("biography", UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("image_profile");
		$arrParams[] = $this->createChildParam("num_followers");
		$arrParams[] = $this->createChildParam("num_following");
		$arrParams[] = $this->createChildParam("num_posts");
		$arrParams[] = $this->createChildParam("url_external");
		$arrParams[] = $this->createChildParam("link");
		$arrParams[] = $this->createChildParam("no_items_code",null,array("child_param_name"=>"hasitems"));
		
		return($arrParams);
	}
	
	/**
	 * get dataset param
	 */
	protected function getAddParams_dataset($paramDataset){
		
		$datasetType = UniteFunctionsUC::getVal($paramDataset, "dataset_type");
		$datasetQuery = UniteFunctionsUC::getVal($paramDataset, "dataset_{$datasetType}_query");
				
		$arrItemHeaders = array();
		$arrItemHeaders = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_GET_DATASET_HEADERS, $arrItemHeaders, $datasetType, $datasetQuery);
		
		$arrChildKeys = array();
		
		foreach($arrItemHeaders as $key){
			$arrChildKeys[] = $this->createChildParam($key);
		}
		
		
		return($arrChildKeys);
	}
	
	
		
	
	
	/**
	 * add dynamic fields child keys
	 * function for override
	 */
	protected function addDynamicChildKeys($arrChildKeys){
		
		return($arrChildKeys);
	}
	
	/**
	 * get child post options - postID, and if use custom fields
	 */
	private function getChildPostOptions(){
		
		$paramPostList = $this->objAddon->getParamByType(UniteCreatorDialogParam::PARAM_POSTS_LIST);
		
		//return by post list
		if(!empty($paramPostList)){
			
			$postExample = UniteFunctionsUC::getVal($paramPostList, "post_example");
			$useCustomFields = UniteFunctionsUC::getVal($paramPostList, "use_custom_fields");
			$useCustomFields = UniteFunctionsUC::strToBool($useCustomFields);
			
			if(!empty($postExample)){
				
				$output = array();
				$output["post_id"] = $postExample;
				$output["use_custom_fields"] = $useCustomFields;
				
				return($output);
			}
						
		}

		$output = array();
		$output["post_id"] = null;
		$output["use_custom_fields"] = false;
		
		return($output);		
	}
	
	
	/**
	 * get params child keys
	 */
	protected function getParamChildKeys(){
		
		$postOptions = $this->getChildPostOptions();
		
		$postID = $postOptions["post_id"];
		$enableCustomFields = $postOptions["use_custom_fields"];
		
		$arrAdditions = array();
		if($enableCustomFields == true)
			$arrAdditions[] = GlobalsProviderUC::POST_ADDITION_CUSTOMFIELDS;
		
		$arrPostParams = $this->getChildParams_post($postID, $arrAdditions);
		
		$arrChildKeys = array();
		$arrChildKeys[UniteCreatorDialogParam::PARAM_POST] = $arrPostParams;
		$arrChildKeys["uc_current_post"] = $arrPostParams;
		
		$arrChildKeys[UniteCreatorDialogParam::PARAM_INSTAGRAM] = $this->getChildParams_instagramMain();
		$arrChildKeys["uc_instagram_item"] = $this->getChildParams_instagramItem();
		
		$arrChildKeys = $this->addDynamicChildKeys($arrChildKeys);
		
		$arrChildKeys["uc_code_examples"] = $this->objChildParams->getChildParams_codeExamples();
		$arrChildKeys["uc_code_examples_js"] = $this->objChildParams->getChildParams_codeExamplesJS();
		
				
		//add dataset params
		$paramDataset = $this->objAddon->getParamByType(UniteCreatorDialogParam::PARAM_DATASET);
		if(!empty($paramDataset))
			$arrChildKeys[UniteCreatorDialogParam::PARAM_DATASET] = $this->getAddParams_dataset($paramDataset);
		
		
		return($arrChildKeys);
	}

	
	
	/**
	 * get additional param keys by type
	 */
	protected function getAddParamKeys(){
		
		$arrAddKeys = array();
		$arrAddKeys[UniteCreatorDialogParam::PARAM_FORM] = $this->getAddParams_form();
		$arrAddKeys["uc_form_item"] = $this->getAddParams_formItem();
		
		
		return($arrAddKeys);
	}
	
	
	/**
	 * get code replacements for params panel
	 */
	protected function getParamTemplateCodes(){
		
		$codeNoItems = "{% if [param_name] == false %}\n\n";
		$codeNoItems .= "	No items text\n\n";
		$codeNoItems .= "{% else %}\n\n";
		$codeNoItems .= "	main output\n\n";
		$codeNoItems .= "{% endif %}";
		
		$arrCode = array();
		$arrCode["no_items_code"] = $codeNoItems;
		
		return($arrCode);
	}
	
	
	private function z____________OTHERS___________(){}
	
	
	/**
	 * get thumb sizes - function for override
	 */
	protected function getThumbSizes(){
		return(null);
	}
	
	/**
	 * get image param add fields
	 */
	protected function getImageAddFields(){
		
		return(null);
	}
	
	/**
	 * put config
	 */
	private function putConfig(){
		
		$options = array();
		$options["url_preview"] = $this->objAddon->getUrlPreview();
		
		$arrThumbSizes = $this->getThumbSizes();
		
		$options["thumb_sizes"] = $arrThumbSizes;
		
		$arrImageAddFields = $this->getImageAddFields();
		
		$options["image_add_fields"] = $arrImageAddFields;
		
		$options["items_type"] = $this->objAddon->getItemsType();
		
		
		$dataOptions = UniteFunctionsUC::jsonEncodeForHtmlData($options, "options");
		
		$params = $this->objAddon->getParams();
		$dataParams = UniteFunctionsUC::jsonEncodeForHtmlData($params, "params");

		
		$paramsItems = $this->objAddon->getParamsItems();
		$dataParamsItems = UniteFunctionsUC::jsonEncodeForHtmlData($paramsItems, "params-items");
		
		$variablesItems = $this->objAddon->getVariablesItem();
		$variablesMain = $this->objAddon->getVariablesMain();
		
		$dataVarItems = UniteFunctionsUC::jsonEncodeForHtmlData($variablesItems, "variables-items");
		$dataVarMain = UniteFunctionsUC::jsonEncodeForHtmlData($variablesMain, "variables-main");
		
		$objOutput = new UniteCreatorOutput();
		$objOutput->setProcessType(UniteCreatorParamsProcessor::PROCESS_TYPE_CONFIG);
		
		$objOutput->initByAddon($this->objAddon);
		
		$arrConstantData = $objOutput->getConstantDataKeys(true);
				
		if($this->showContstantVars == false)
			$arrConstantData = array();
		
		if(!empty($this->arrCustomConstants))
			$arrConstantData += $this->arrCustomConstants;
		
			
		$dataPanelKeys = UniteFunctionsUC::jsonEncodeForHtmlData($arrConstantData, "panel-keys");
		
		$arrItemConstantData = $objOutput->getItemConstantDataKeys();
		$dataItemPanelKeys = UniteFunctionsUC::jsonEncodeForHtmlData($arrItemConstantData, "panel-item-keys");
		
		//child keys of some fields
		$arrPanelChildKeys = $this->getParamChildKeys();
		
		$dataPanelChildKeys = UniteFunctionsUC::jsonEncodeForHtmlData($arrPanelChildKeys, "panel-child-keys");
		
		$arrPanelAddKeys = $this->getAddParamKeys();

		$dataPanelAddKeys = UniteFunctionsUC::jsonEncodeForHtmlData($arrPanelAddKeys, "panel-add-keys");
		
		$arrPanelTemplateCode = $this->getParamTemplateCodes();
		$dataPanelCode = UniteFunctionsUC::jsonEncodeForHtmlData($arrPanelTemplateCode, "panel-template-code");
		
		$dataSkipParams = UniteFunctionsUC::jsonEncodeForHtmlData($this->arrSkipPanelParams, "panel-skip-params");
		
		
		?>
		
		<div id="uc_edit_item_config" style="display:none"
			<?php echo UniteProviderFunctionsUC::escAddParam($dataParams)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataParamsItems)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataPanelKeys)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataPanelAddKeys)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataItemPanelKeys)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataVarItems)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataVarMain)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataOptions)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataPanelChildKeys)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataPanelCode)?>
			<?php echo UniteProviderFunctionsUC::escAddParam($dataSkipParams)?>
		></div>
		
		<?php 
	}
	
	
	/**
	 * put js
	 */
	private function putJs(){
		?>
		
		<script type="text/javascript">
		
		jQuery(document).ready(function(){
			var objAdmin = new UniteCreatorAdmin();
			objAdmin.initEditAddonView();
		});
		
		</script>
		
		<?php 
	}
	
	
	/**
	 * bulk dialog
	 */
	private function putBulkDialog(){
		?>
		<div id="uc_dialog_bulk" title="<?php esc_html_e("Bulk Operations", "unlimited_elements")?>" class="unite-inputs" style="display:none">
			
			bulk operations dialog
			
		</div>
		<?php 
	}
	
	
	/**
	 * get contents of bulk dialog from ajax
	 */
	public function getBulkDialogContents($data){
		
		$addonID = UniteFunctionsUC::getVal($data, "addon_id");
		UniteFunctionsUC::validateNotEmpty($addonID,"addon id");
		
		$paramType = UniteFunctionsUC::getVal($data, "param_type");
		
		$paramData = UniteFunctionsUC::getVal($data, "param_data");
		
		$paramTitle = UniteFunctionsUC::getVal($paramData, "title"); 
		$paramName = UniteFunctionsUC::getVal($paramData, "name"); 
		
		
		//get data
		$addon = new UniteCreatorAddon();
		$addon->initByID($addonID);
		$addonType = $addon->getType();
		
		$catID = $addon->getCatID();
		UniteFunctionsUC::validateNotEmpty($catID);
		
		$addons = new UniteCreatorAddons();
		$arrAddons = $addons->getCatAddons($catID, false, null, $addonType);
		
		//make html
		
		ob_start();
		
		$addonTitle = $addon->getTitle();
		
		?>
		<br>
		
		<?php echo esc_html($paramType) ?> param: <b> <?php echo esc_html($paramTitle)?> ( <?php echo esc_html($paramName)?> ) </b>
		<span class="hor_sap40"></span>
		Addon: <b> <?php echo esc_html($addonTitle)?> </b>
		
		<br><br>
		
		<div class="unite-dialog-inner-constant">
		
		<div class="uc-dialog-loader loader_text" style="display:none"><?php esc_html_e("Updating Addons", "unlimited_elements")?>...</div>
		
		<table class="unite_table_items">
		
			<tr>
				<th class="">
					<input type='checkbox' title="<?php esc_html_e("Select All Addons", "unlimited_elements")?>" class="uc-check-all">
				</th>
				<th><b><?php esc_html_e("Addon Title", "unlimited_elements")?></b></th>
				<th><b><?php esc_html_e("Status", "unlimited_elements")?></b></th>
			</tr>
		
		<?php 
		
		$numSelected = 0;
		
		foreach($arrAddons as $index=>$catAddon){
			$title = $catAddon->getTitle();
			$catAddonID = $catAddon->getID();
			if($catAddonID == $addonID)
				continue;
				
			$rowClass = $index%2?"unite-row1":"unite-row2";
			
			$isMain = ($paramType == "main");
			$isExists = $catAddon->isParamExists($paramName, $isMain);
			
			$status = "<span class='unite-color-red'>not exists</span>";
			if($isExists)
				$status = "<span class='unite-color-green'>exists</span>";
			
			$checked = "";
			if($isExists == false){
				$checked = " checked";
				$numSelected++;
				$rowClass .= " unite-row-selected";
			}
			
			?>
			<tr class="<?php echo esc_attr($rowClass)?>">
				<td>
					<input type='checkbox' data-id="<?php echo esc_attr($catAddonID)?>" <?php echo UniteProviderFunctionsUC::escAddParam($checked)?> class="uc-check-select">
				</td>
				<td><?php echo esc_html($title)?></td>
				<td><?php echo esc_html($status)?></td>
			</tr>
			<?php 
		}
				
		?>
		</table>
		</div>
		
		<br>

		<span class='uc-section-selected'>
			<span id='uc_bulk_dialog_num_selected'><?php echo esc_html($numSelected)?></span> <?php esc_html_e("selected")?>
		</span>
		
		<span class="hor_sap"></span>
		
		<a href="javascript:void(0)" data-action="update" class="uc-action-button unite-button-primary"><?php esc_html_e("Add / Update in Addons", "unlimited_elements")?></a>
		
		<span class="hor_sap40"></span>
		
		<a href="javascript:void(0)" data-action="delete" class="uc-action-button unite-button-secondary"><?php esc_html_e("Delete From Addons", "unlimited_elements")?></a>
		
		
		<?php 
		
		$html = ob_get_contents();
		ob_end_clean();
		
		
		$response = array();
		$response["html"] = $html;
		
		return($response);
	}
	
	/**
	 * put params and variables dialog
	 */
	private function putDialogs(){
		
		$addonType = $this->objAddon->getType();
		
		//dialog param		
		$objDialogParam = UniteCreatorDialogParam::getInstance($addonType);
		
		$objDialogParam->init(UniteCreatorDialogParam::TYPE_MAIN, $this->objAddon);
		$objDialogParam->outputHtml();
		
		//dialog variable item
		
		$objDialogVariableItem = UniteCreatorDialogParam::getInstance($addonType);
		$objDialogVariableItem->init(UniteCreatorDialogParam::TYPE_ITEM_VARIABLE, $this->objAddon);
		$objDialogVariableItem->outputHtml();
		
		//dialog variable main
		$objDialogVariableMain = UniteCreatorDialogParam::getInstance($addonType);
		$objDialogVariableMain->init(UniteCreatorDialogParam::TYPE_MAIN_VARIABLE, $this->objAddon);
		$objDialogVariableMain->outputHtml();
		
		$this->putBulkDialog();
	}
	
	
	/**
	 * put some html that will appear before tabs
	 */
	private function putHtml_beforeTabs(){
		?>
				<div id="uc_update_addon_error" class="unite_error_message" style="display:none"></div>
		<?php 
	}
	
	
	/**
	 * put html
	 */
	private function putHtml(){
		
		if($this->showHeader == true)
			$this->putHtml_top();
		else
			require HelperUC::getPathTemplate("header_missing");
		?>
		<div class="content_wrapper unite-content-wrapper">
		<?php 
		if($this->showToolbar == true)
			$this->putHtml_actionButtons();
		
		$this->putHtml_beforeTabs();
			
		$this->putHtml_tabs();
		$this->putHtml_content();
		
		$this->putConfig();
		$this->putJs();
		
		$this->putDialogs();
		
		?>
		</div>
		<?php 
	}
	
	
}

