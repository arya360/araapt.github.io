<?php
use Elementor\TemplateLibrary;

defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');


class UniteCreatorElementorIntegrate{
	
	const ADDONS_CATEGORY_TITLE = "Unlimited Elements";
	const ADDONS_CATEGORY_NAME = "unlimited_elements";
	const ADDONS_TYPE = "elementor";
	const DEFAULT_ICON = "fa fa-gears";
	const TEMPLATE_TYPE_ARCHIVE = "archive";
	
	private $enableImportTemplate = true;
	private $enableExportTemplate = true;
	private $enablePageBackground = false;
	
	public static $isConsolidated = false;
	
	private $pathPlugin;
	private $pathControls;
	private $arrAddons;
	public static $isLogMemory;
	public static $counterWidgets=0;
	public static $counterControls=0;
	private static $numRegistered = 0;
	public static $arrCatsCache = array();
	public static $templateType;
	
	
	/**
	 * init some vars
	 */
	public function __construct(){
		
		$this->pathPlugin = __DIR__."/";
		$this->pathControls = $this->pathPlugin."controls/";
		
		self::$isLogMemory = $this->getIsLogMemory();
		
	}
	
	
	/**
	 * determine if log memory or not
	 */
	private function getIsLogMemory(){
		
		//check general setting
		$enableMemoryTest = HelperProviderCoreUC_EL::getGeneralSetting("enable_memory_usage_test");
		$enableMemoryTest = UniteFunctionsUC::strToBool($enableMemoryTest);
		
		if($enableMemoryTest == false)
			return(false);
				
		//filter unnessasery urls
		$url = GlobalsUC::$current_page_url;
		
		if(strpos($url, "js.map") !== false)
			return(false);
		
		return(true);
	}
	
	
	private function z__________TEMP___________(){}
	
	
	/**
	 * run test widget
	 */
	public function runTestWidget(){
		
		require_once $this->pathPlugin."test_widget.class.php";
		
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new ElementorWidgetTest());
	}
	
	
	/**
	 * run second widget
	 */
	public function runTestRealWidget(){
		
		$objAddons = new UniteCreatorAddons();
		$arrAddons = $objAddons->getArrAddons();
		
		$addon = $arrAddons[0];
		
		$widget = new UniteCreatorElementorWidget();
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type($widget);
		
	}
	
	
	
	private function a___________REGISTER_COMPONENTS__________(){}
	
	
	/**
	 * get arr addons only once
	 */
	public function getArrAddons($getRecordsOnly = false){
		
		if(!empty($this->arrAddons))
			return($this->arrAddons);
		
		$objAddons = new UniteCreatorAddons();
		$params = array();
		$params["filter_active"] = "active";
		$params["addontype"] = self::ADDONS_TYPE;
		
		if($getRecordsOnly == true){
			
			$arrAddons = $objAddons->getArrAddonsShort("", $params, self::ADDONS_TYPE);
			
			return($arrAddons);
		}
		else
			$arrAddons = $objAddons->getArrAddons("", $params, self::ADDONS_TYPE);
					
		$this->arrAddons = $arrAddons;
		
		return($arrAddons);
	}
	
	
	
	/**
	 * register addons
	 */
	private function registerWidgets_addons($arrAddons, $isRecords = false){
		
		foreach ($arrAddons as $addon) {
			
			self::$counterWidgets++;
			
            $isEnoughtMemory = UniteFunctionsUC::isEnoughtPHPMemory();
            
			if($isEnoughtMemory == false){
				
				self::logMemoryUsage("Skip widget register (no memory): ".$name.", counter: ".self::$counterWidgets, true);
				
				continue;
			}
            
			self::$numRegistered++;
			
			if($isRecords == true){
				$name = $addon["name"];
				
			}else{
				$name = $addon->getName();
			}

			
			//some protection
			$isAlphaNumeric = UniteFunctionsUC::isAlphaNumeric($name);
			if($isAlphaNumeric == false)
				return(false);
			
			$className = "UCAddon_".$name;
            
			if($isEnoughtMemory == false)
				$className .= "_no_memory";
			
			self::logMemoryUsage("Before Register Widget: ".$name. ", counter: ".self::$counterWidgets);
			
		    $code = "class {$className} extends UniteCreatorElementorWidget{}";
		    eval($code);
            
		    $widget = new $className();
                    \Elementor\Plugin::instance()->widgets_manager->register_widget_type($widget);
            
            self::logMemoryUsage("Register Widget: ".$name.", counter: ".self::$counterWidgets);
			
			
		}
		
	}
	
	/**
	 * register elementor widget by class name
	 */
	private function registerWidgetByClassName($className){
		
		$code = "class {$className} extends UniteCreatorElementorWidget{}";
	    eval($code);
		
		$widget = new $className();
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type($widget);
		
	}
	
	
	/**
	 * register consolidated widget
	 */
	private function registerWidgets_consolidated($objCat, $arrCat){
				
		$title = $objCat->getTitle();
		$alias = $objCat->getAlias();
		
		$arrAddons = UniteFunctionsUC::getVal($arrCat, "addons");
		
		if(empty($arrAddons))
			return(false);
		
		self::$arrCatsCache[$alias] = array("title"=>$title, "objcat"=>$objCat,"addons"=>$arrAddons);
		
		$className = "UCAddon_uccat_".$alias;
	    $this->registerWidgetByClassName($className);
		
	}
	
	
	/**
	 * register consolidated category widgets
	 */
	private function registerWidgets_categories(){
		
		$objAddons = new UniteCreatorAddons();
		
		$arrCats = $objAddons->getAddonsWidthCategories(true, false, self::ADDONS_TYPE, array("get_cat_objects"=>true));
    					
		foreach($arrCats as $cat){
			$id = UniteFunctionsUC::getVal($cat, "id");
			
			//uncategorised
			if($id == 0){
				$addons = UniteFunctionsUC::getVal($cat, "addons");
				if(!empty($addons))
					$this->registerWidgets_addons($addons);
				
				continue;
			}
						
			$objCat = UniteFunctionsUC::getVal($cat, "objcat");
			
			//register consolidated widgets
			$this->registerWidgets_consolidated($objCat, $cat);			
		}
		
	}
	
	
	
	/**
	 * register elementor widgets from the library
	 */
	private function registerWidgets(){
        
		self::logMemoryUsage("before widgets registered");
		
		self::$numRegistered = 0;
				
		if(self::$isConsolidated)
			$this->registerWidgets_categories();
		else{
			
			$arrAddons = $this->getArrAddons(true);
						
			$this->registerWidgets_addons($arrAddons, true);
		}
		
		self::logMemoryUsage("widgets registered: ".self::$numRegistered, true);
				
	}
	
	/**
	 * get template type
	 */
	public static function getCurrentTemplateType(){
		
		if(!empty(self::$templateType))
			return(self::$templateType);
		
		$post = get_post();
				
		if(empty($post))
			return("");
			
		$postType = $post->post_type;
				
		if($postType != GlobalsUnlimitedElements::POSTTYPE_ELEMENTOR_LIBRARY)
			return("");
		
		$templateType = get_post_meta($post->ID, GlobalsUnlimitedElements::META_TEMPLATE_TYPE, true);
				
		return($templateType);		
	}
	
	
	/**
	 * init template vars variables
	 */
	private function initTemplateTypeVars(){
				
		$document = \Elementor\Plugin::$instance->documents->get_current();
				
		if(empty($document)){
			self::$templateType = self::getCurrentTemplateType();
			return(false);
		}
		
		self::$templateType = $document->get_template_type();
		
	}
	
	
    /**
     * on widgets registered event
     * register elementor widgets from the library
     */
    public function onWidgetsRegistered() {
    	
    	$this->initTemplateTypeVars();
    	
    	$this->includePluginFiles();
    	
    	$this->registerWidgets();
    	
    }
	
    
    /**
     * register controls
     */
    public function onRegisterControls($controls_manager) {
    	
    	self::logMemoryUsage("before controls registered");
    	    	
    	//add hr control
    	require $this->pathControls."control_hr.php";
        $controls_manager->register_control('uc_hr', new Elementor\Control_UC_HR());
    	
        //add audio control
    	require $this->pathControls."control_audio.php";
        $controls_manager->register_control('uc_mp3', new Elementor\Control_UC_AUDIO());
    	
        //add addons selector control
    	require $this->pathControls."control_addon_selector.php";
        $controls_manager->register_control('uc_addon_selector', new Elementor\Control_UC_AddonSelector());
        
        //add select post type control
    	require $this->pathControls."control_select_posttype.php";
        $controls_manager->register_control('uc_select_special', new Elementor\Control_UC_SelectSpecial);

        //add select shape control
    	require $this->pathControls."control_shape.php";
        $controls_manager->register_control('uc_shape_picker', new Elementor\Control_UC_Shape);
        
        
        self::logMemoryUsage("after controls registered");
        
    }
    
    
	/**
	 * include plugin files that should be included only after elementor include
	 */
	private function includePluginFiles(){
				
		require_once $this->pathPlugin."elementor_widget.class.php";
		require_once $this->pathPlugin."elementor_widget.class.php";
		require_once $this->pathPlugin . 'elementor_content.class.php';
		
	}
    
		
    
    /**
     * register front end scripts
     */
    public function onRegisterFrontScripts(){
    	    	
    }
    
    
    /**
     * get category alias from id
     */
    public static function getCategoryName($catID){
		$catName = "uc_category_{$catID}";
		
		return($catName);
    }
    
    
    /**
     * add all categories
     */
    private function addUCCategories(){
    	
    	$objCats = new UniteCreatorCategories();
    	$arrCats = $objCats->getArrCats(self::ADDONS_TYPE);
    	
    	$objElementsManager = \Elementor\Plugin::instance()->elements_manager;
    	
    	//add general category
    	$objElementsManager->add_category(self::ADDONS_CATEGORY_NAME, array("title"=>self::ADDONS_CATEGORY_TITLE,"icon"=>self::DEFAULT_ICON), 2);
    	
    	
    	foreach($arrCats as $index=>$cat){
    		
    		$numAddons = UniteFunctionsUC::getVal($cat, "num_addons");
    		$numAddons = (int)$numAddons;
    		if($numAddons == 0)
    			continue;
    		
    		$catID = UniteFunctionsUC::getVal($cat, "id");
    		$catTitle = UniteFunctionsUC::getVal($cat, "title");
    		$catName = self::getCategoryName($catID);
    		
    		$icon = UniteFunctionsUC::getVal($cat, "icon");
    		if(empty($icon))
    			$icon = self::DEFAULT_ICON;
    		
    		$objElementsManager->add_category($catName, array("title"=>$catTitle,"icon"=>$icon), 2);
    	}
    	
    }
    
    
    /**
     * run after register controls
     */
    public function onFrontendAfterRegisterControls(){
    	
    	self::logMemoryUsage("End registering widget controls, num registered: ".UniteCreatorElementorIntegrate::$counterControls, true);
    	
    }
    
    
    /**
     * on elementor init
     */
    public function onElementorInit(){
    	
    	$this->addUCCategories();
    	
    }
	
    private function a____________MODIFY_CONTENT___________(){}
    
	/**
	 * modify front end data
	 */
	public function modifyContentData($data){
		
		return($data);
	}
    
	/**
	 * on before render
	 * Enter description here ...
	 */
	public function onBeforeRender($obj){
		
		if(GlobalsUC::$inDev == false)
			return(false);
		
		UniteFunctionsUC::showTrace();
		exit();
		
		dmp($obj);
		//exit();
		
	}
	
	/**
	 * print test background widget
	 */
	private function printTestBackgroundWidget(){
		?>
		<style>
			.uc-background-widget{
				padding:0px;
				margin:0px;
				position:fixed;
				width:100% !important;
				height:100% !important;
				z-index:0;
			}
			.uc-background-widget .uc-background-test{
				padding:0px;
				margin:0px;
				position:fixed;
				width:100% !important;
				height:100% !important;
				background-color:green;
			}
		</style>
		<div class="uc-background-widget" xstyle="display:none">
			<div class="uc-background-test">Max Background</div>
		</div>
		
		<script>

			jQuery(document).ready(function(){
				var objBG = jQuery(".uc-background-widget");

				if(objBG.length == 0)
					return(false);

				var objBody = jQuery("body");
				if(objBody.length == 0)
					return(false);
				
				objBG.detach().prependTo(objBody);
				
			});
		
		</script>
		<?php 
		
	}
	
	
	/**
	 * print footer html
	 */
	public function onPrintFooterHtml(){
		
		$this->printTestBackgroundWidget();
		
	}
	
	
	/**
	 * on page style controls add
	 * from controls-stack.php
	 */
	public function onPageStyleControlsAdd($objControls, $args){
			
          $objControls->add_control(
                'title', array(
                'label' => "Choose Unlimited Background",
                'type' => 'text',
                )
           );
           
	}
	
	/**
	 * test background
	 */
	private function testBackground(){
		
		$this->enablePageBackground = true;
				
		add_action("elementor/element/post/section_page_style/before_section_end", array($this, "onPageStyleControlsAdd"),10, 2);
		
    	//add_filter("elementor/frontend/builder_content_data",array($this, 'modifyContentData'));
    	//add_action('wp_print_footer_scripts', array($this, 'onPrintFooterHtml'));
	}
	
	private function a____________IMPORT_ADDONS___________(){}
	
    
    /**
     * return if it's elmenetor library page
     */
    private function isElementorLibraryPage(){
		global $current_screen;
		if ( ! $current_screen ) {
			return false;
		}
		
		if($current_screen->base != "edit")
			return(false);
		
		if($current_screen->post_type != GlobalsUnlimitedElements::POSTTYPE_ELEMENTOR_LIBRARY)
			return(false);
		
		return(true);		
    }
    
    
    
   	/**
	* put import vc layout html
	*/
	private function putDialogImportLayoutHtml(){
			
			$dialogTitle = __("Import Unlimited Elements Layout to Elementor",UNLIMITED_ADDONS_TEXTDOMAIN);
			
			
			?>
		<div id="uc_dialog_import_layouts" class="unite-inputs" title="<?php echo esc_attr($dialogTitle)?>" style="display:none;">
			
			<div class="unite-dialog-top"></div>
			
			<div class="unite-inputs-label">
				<?php esc_html_e("Select vc layout export file (zip)", UNLIMITED_ADDONS_TEXTDOMAIN)?>:
			</div>
			
			<div class="unite-inputs-sap-small"></div>
			
			<form id="dialog_import_layouts_form" name="form_import_layouts">
				<input id="dialog_import_layouts_file" type="file" name="import_layout">
				
			</form>	
			
			<div class="unite-inputs-sap-double"></div>
			
			<div class="unite-inputs-label" >
				<label for="dialog_import_layouts_file_overwrite">
					<?php esc_html_e("Overwrite Addons", UNLIMITED_ADDONS_TEXTDOMAIN)?>:
				</label>
				<input type="checkbox" id="dialog_import_layouts_file_overwrite"></input>
			</div>
			
			
			<div class="unite-clear"></div>
			
			<?php 
				$prefix = "uc_dialog_import_layouts";
				$buttonTitle = __("Import VC Layout", UNLIMITED_ADDONS_TEXTDOMAIN);
				$loaderTitle = __("Uploading layout file...", UNLIMITED_ADDONS_TEXTDOMAIN);
				$successTitle = __("Layout Imported Successfully", UNLIMITED_ADDONS_TEXTDOMAIN);
				HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
			?>
			
			<div id="div_debug"></div>
			 
		</div>		
		
		<?php 
	}
	
	
	/**
	 * put import layout button
	 */
	private function putImportLayoutButton(){
		
		$nonce = UniteProviderFunctionsUC::getNonce();
		
		?>
		<style>
		
		#uc_import_layout_area{
			margin: 50px 0 30px;
    		text-align: center;			
		}
		
		#uc_form_import_template {
		    background-color: #fff;
		    border: 1px solid #e5e5e5;
		    display: inline-block;
		    margin-top: 30px;
		    padding: 30px 50px;
		}
		
		#uc_import_layout_area_title{
		 	color: #555d66;
    		font-size: 18px;			
		}
    	
		</style>
		
		<div style="display:none">
		
			<a id="uc_button_import_layout" href="javascript:void(0)" class="page-title-action"><?php esc_html_e("Import Template With Addons", "unlimited_elements")?></a>
			
			<div id="uc_import_layout_area" style="display:none">
				<div id="uc_import_layout_area_title"><?php _e( 'Choose an Elementor template .zip file, that you exported using "export with addons" button'); ?></div>
				<form id="uc_form_import_template" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" enctype="multipart/form-data">
					<input type="hidden" name="action" value="unitecreator_elementor_import_template">
					<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce) ?>">
					<fieldset>
						<input type="file" name="file" accept=".json,.zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" required>
						<input type="submit" class="button" value="<?php esc_html_e( 'Import Now', "unlimited_elements" ); ?>">
					</fieldset>
				</form>
			</div>
			
		</div>
		
		<?php 
	}
    
	
	
    /**
     * Enter description here ...
     */
    public function onAdminFooter(){
		
    	$isTemplatesPage = $this->isElementorLibraryPage();
    	
    	if($isTemplatesPage == false)
    		return(false);
    	
    	$this->putImportLayoutButton();
    	
    }
    
    
    /**
     * on add scripts to template library page
     */
    public function onAddScripts(){
    	$isTemplatesPage = $this->isElementorLibraryPage();
    	    	
    	if($isTemplatesPage == true)
    		HelperUC::addScriptAbsoluteUrl(HelperProviderCoreUC_EL::$urlCore."elementor/assets/template_library_admin.js", "unlimited_addons_template_library_admin");
    }
	    
    /**
     * get shape scripts for footer 
     */
    private function getShapePickerScripts(){
    	
		$objShapes = new UniteShapeManagerUC();
		$jsonShapes = $objShapes->getJsonShapes();
    	
		$js = "";
		$js .= 'var g_ucArrSvgShapes = '.$jsonShapes.';'."\n";
		
		
		return($js);
    }
    
    
    /**
     * get editor page scripts
     */
    private function getEditorPageCustomScripts(){
    	
    	$arrAddons = $this->getArrAddons();
    	
    	$objAddons = new UniteCreatorAddons();
    	$arrPreviewUrls = $objAddons->getArrAddonPreviewUrls($arrAddons, "title");
		    	
    	$jsonPreviews = UniteFunctionsUC::jsonEncodeForClientSide($arrPreviewUrls);
    	
    	$urlAssets = GlobalsUC::$url_assets;
    	
    	$script = "";
    	$script .= "\n\n // ----- unlimited elements scripts ------- \n\n";
    	//$script .= "var g_ucJsonAddonPreviews={$jsonPreviews};\n";
    	$script .= "var g_ucUrlAssets='{$urlAssets}';\n";
		$script .= "\n";
		
		$shapeScripts = $this->getShapePickerScripts();
		
		$script .= $shapeScripts;
		
		$script .= "\n";
		
    	return($script);
    }
    
    
    /**
     * on enqueue front end scripts
     */
    public function onEnqueueEditorScripts(){
    	
    	HelperUC::addScriptAbsoluteUrl(HelperProviderCoreUC_EL::$urlCore."elementor/assets/uc_editor_admin.js", "unlimited_elments_editor_admin");
    	HelperUC::addStyleAbsoluteUrl(HelperProviderCoreUC_EL::$urlCore."elementor/assets/uc_editor_admin.css", "unlimited_elments_editor_admin_css");
    	
    	$script = $this->getEditorPageCustomScripts();
    	UniteProviderFunctionsUC::printCustomScript($script, true);
    }
        
    
    /**
     * on ajax import template
     */
    public function onAjaxImportLayout(){
    	    	
    	try{
    		
	    	$nonce = UniteFunctionsUC::getPostVariable("nonce", "", UniteFunctionsUC::SANITIZE_TEXT_FIELD);
	    	UniteProviderFunctionsUC::verifyNonce($nonce);
	    		    	
	    	$arrTempFile = UniteFunctionsUC::getVal($_FILES, "file");
	    	UniteFunctionsUC::validateNotEmpty($arrTempFile,"import file");
	    		    	
    		$exporter = new UniteCreatorLayoutsExporterElementor();
	    	$exporter->importElementorTemplateNew($arrTempFile);
    	
	    	
	    	wp_redirect(GlobalsUnlimitedElements::$urlTemplatesList);
	    	exit();
	    	
    	}catch(Exception $e){
    		
    		HelperHtmlUC::outputException($e);
    		exit();
    	}
    	
    	    	
    }
    
    /**
     * log memory usages
     */
    public static function logMemoryUsage($operation, $updateOption=false){
    	
    	if(self::$isLogMemory == false)
    		return(false);
    	
    	HelperUC::logMemoryUsage($operation, $updateOption);
    	
    	$isEnoughtMemory = UniteFunctionsUC::isEnoughtPHPMemory();
    	if($isEnoughtMemory == false)
    		HelperUC::logMemoryUsage("Low Memory!!!", true);
    	
    }
    
    
    
	/**
	 * check the screen
	 */
	private function isTemplatesScreen(){
		global $current_screen;

		if ( ! $current_screen ) {
			return false;
		}
		
		if($current_screen->base == "edit" && $current_screen->post_type == GlobalsUnlimitedElements::POSTTYPE_ELEMENTOR_LIBRARY)
			return(true);
		
		return(false);
	}
    
    
	/**
	 * get export with addons link
	 */
	private function getTemplateExportWithAddonsLink($postID){
		
		return add_query_arg(
			[
				'action' => 'unitecreator_elementor_export_template',
				'library_action' => 'export_template_withaddons',
				'source' => 'unlimited-elements',
				'_nonce' => UniteProviderFunctionsUC::getNonce(),
				'template_id' => $postID,
			],
			admin_url( 'admin-ajax.php' )
		);
		
	}
	
	
	/**
	 * return if template support export
	 */
	public function isTemplateSupportExports( $template_id ) {
		$export_support = true;
		
		$export_support = apply_filters( 'elementor/template_library/is_template_supports_export', $export_support, $template_id );
		
		return $export_support;
	}
	
	
	/**
	 * post row actions
	 */
	public function postRowActions($actions, WP_Post $post ){
		
		//-------  validation
		
		$isTemplatesScreen = $this->isTemplatesScreen();
		
		if($isTemplatesScreen == false)
			return($actions);
				
		$postID = $post->ID;
		
		$isSupportExport = $this->isTemplateSupportExports($postID);
		
		if($isSupportExport == false)
			return($actions);
		
		// --------- add action
		
		$actions['export-template-withaddons'] = sprintf( '<a href="%1$s">%2$s</a>', $this->getTemplateExportWithAddonsLink($postID), __( 'Export With Addons', "unlimited_elements" ) );
		
		return($actions);
	}
    
	/**
	 * export template
	 */
	public function onAjaxExportTemplate(){
		
		$nonce = UniteFunctionsUC::getGetVar("_nonce", "", UniteFunctionsUC::SANITIZE_TEXT_FIELD);
		UniteProviderFunctionsUC::verifyNonce($nonce);
		
		$libraryAction = UniteFunctionsUC::getGetVar("library_action", "", UniteFunctionsUC::SANITIZE_TEXT_FIELD);
		if($libraryAction != "export_template_withaddons")
			UniteFunctionsUC::throwError("Wrong action: $libraryAction");
		
		$templateID = UniteFunctionsUC::getGetVar("template_id", "", UniteFunctionsUC::SANITIZE_TEXT_FIELD);
		$templateID = (int)$templateID;
		
		$post = get_post($templateID);
		
		if(empty($post))
			UniteFunctionsUC::throwError("template not found");
		
		$postType = $post->post_type;
		if($postType != GlobalsUnlimitedElements::POSTTYPE_ELEMENTOR_LIBRARY)
			UniteFunctionsUC::throwError("wrong post type");
		
		
		$objExporter = new UniteCreatorLayoutsExporterElementor();
		$objExporter->exportElementorTemplate($templateID);
		
	}
    
	
    /**
     * init the elementor integration
     */
    public function initElementorIntegration(){
		
    	$isEnabled = HelperProviderCoreUC_EL::getGeneralSetting("el_enable");
    	$isEnabled = UniteFunctionsUC::strToBool($isEnabled);
    	if($isEnabled == false)
    		return(false);
    	
    	self::$isConsolidated = HelperProviderCoreUC_EL::getGeneralSetting("consolidate_addons");
    	self::$isConsolidated = UniteFunctionsUC::strToBool(self::$isConsolidated);
    	
    	$enableExportImport = HelperProviderCoreUC_EL::getGeneralSetting("enable_import_export");
    	$enableExportImport = UniteFunctionsUC::strToBool($enableExportImport);

    	if($enableExportImport == false){
    		$this->enableExportTemplate = false;
    		$this->enableImportTemplate = false;
    	}
    	
    	add_action('elementor/widgets/widgets_registered', array($this, 'onWidgetsRegistered'));
    	
    	add_action('elementor/frontend/after_register_scripts', array($this, 'onRegisterFrontScripts'), 10);
    	add_action('elementor/editor/after_enqueue_scripts', array($this, 'onEnqueueEditorScripts'), 10);
    	
    	add_action('elementor/controls/controls_registered', array($this, 'onRegisterControls'));
    	
    	add_action('elementor/frontend/after_enqueue_scripts', array($this, 'onFrontendAfterRegisterControls'));
		    	
    	//if(Globalsuc::$inDev == true){
			
    		//if(defined("UC_TEST_BACKGROUND"))
    			//$this->testBackground();
    		
    		//add_action('elementor/frontend/before_render', array($this, 'onBeforeRender'));
    	//}
    	
    	add_action('elementor/init', array($this, 'onElementorInit'));
		    	
    	// ------ admin related only ----------
    	    	
    	if(is_admin() == false)
    		return(false);
		
    	if($this->enableExportTemplate == true){
		
    		add_filter( 'post_row_actions', array($this, 'postRowActions' ), 20, 2 );
			
    		add_action('wp_ajax_unitecreator_elementor_export_template', array($this, 'onAjaxExportTemplate'));
    		
    	}
    	
    	//import tepmlate
    	if($this->enableImportTemplate == true){
    		
			add_action( 'admin_footer', array($this, 'onAdminFooter') );
			add_action( 'admin_enqueue_scripts', array($this, 'onAddScripts') );
	    	
			add_action('wp_ajax_unitecreator_elementor_import_template', array($this, 'onAjaxImportLayout'));
    	}
    	
    }
    
}
