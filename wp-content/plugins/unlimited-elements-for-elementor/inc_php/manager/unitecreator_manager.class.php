<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');


class UniteCreatorManager{
	
	const TYPE_ADDONS = "addons";
	const TYPE_ITEMS_INLINE = "inline";
	const TYPE_PAGES = "pages";
	
	const VIEW_TYPE_INFO = "info";		//addons view type
	const VIEW_TYPE_THUMB = "thumb";
	
	protected $type = null, $arrText = array(), $arrOptions = array();
	protected $viewType = null;		//view type in addition to type
	protected $managerName = null;	//manager name
	protected $arrPassData = null;	//pass data via js
	
	protected $hasCats = true;
	
	protected $objCats = null;
	protected $selectedCategory = "";
	
	private $managerAddHtml = "";
	private $errorMessage = null;
	protected $itemsLoaderText = "";
	protected $textItemsSelected = "";
	protected $enableCatsActions = true;
	protected $listClassType = null;
	protected $enableStatusLineOperations = true;
	
	
	protected function a_REWRITE_FUNCTIONS(){}
	
	
	/**
	 * get manager by addon type
	 */
	public static function getObjManagerByAddonType($addonType, $data = array()){
		
		$objAddonType = UniteCreatorAddonType::getAddonTypeObject($addonType);
		
		$manager = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_GET_MANAGER_OBJECT_BYDATA, null, $data);
		
		if(empty($manager)){
			if($objAddonType->isLayout == true)
				$manager = new UniteCreatorManagerLayouts();
			else
				$manager = new UniteCreatorManagerAddons();
		}
		
		//init the manager inside
		$manager->setManagerNameFromData($data);
		
				
		return($manager);
	}
	
	
	/**
	 * before init - function for override
	 */
	protected function beforeInit($type){
				
	}
	
	/**
	 * run after init - function for override
	 */
	protected function afterInit($type){
				
	}
	
	
	/**
	 * put items buttons
	 */
	protected function putItemsButtons(){
	
		?>
	put buttons from child classes
	<?php
	}
	
	
	/**
	 * put filters - function for override
	 */
	protected function putItemsFilters(){}
	
	
	/**
	 * ge tmenu single item
	 */
	protected function getMenuSingleItem(){
	
		$arrMenuItem = array();
		$arrMenuItem["no_action"] = esc_html__("No Action","unlimited_elements");
	
		return($arrMenuItem);
	}
	
	/**
	 * get item field menu
	 */
	protected function getMenuField(){
	
		$arrMenuField = array();
		$arrMenuField["no_action"] = esc_html__("No Action","unlimited_elements");
	
		return($arrMenuField);
	}
	
	
	/**
	 * put additional html here
	 */
	protected function putAddHtml(){
		dmp("put add html here by child class");
	}
	
	
	/**
	 * get no items text
	 */
	protected function getNoItemsText(){
		
		$text = esc_html__("No Items", "unlimited_elements");
		
		return($text);
	}
	
	protected function a_SET_DATA_BEFORE_PUT(){}
	
	
	/**
	 * set manager add html, must be called before put
	 */
	protected function setManagerAddHtml($addHtml){
		$this->managerAddHtml = $addHtml;
	}
	
	protected function a_CATEGORIES_RELATED(){}
	
	
	/**
	 * get category list
	 */
	protected function getCatList(){
		dmp("getCatList - function for override!!!");exit();
	}

	
	/**
	 * put categories html
	 */
	private function putHtmlCats(){
		
		$htmlCatList = $this->getCatList();
		$showAllButtons = false;
		
		?>
		<div id="categories_wrapper" class="categories_wrapper unselectable">

			<?php if($this->enableCatsActions == true):?>
				<div class="manager-cats-buttons">
						<span class="manager-cats-title"><?php esc_html_e("Categories","unlimited_elements")?></span>
						<a id="button_add_category" data-action="add_category" type="button" class="uc-cat-action-button uc-button-add-cat">+</a>
				</div>

			<?php endif?>

			<div id="cats_section" class="cats_section">
				<div class="cat_list_wrapper">			 
					<ul id="list_cats" class="list_cats">
						<?php echo UniteProviderFunctionsUC::escCombinedHtml($htmlCatList)?>
					</ul>					
				</div>
			</div>			 	
		</div>
		<?php
	}

	
	/**
	 * put category edit dialog
	 */
	protected function putDialogEditCategory(){
		?>
			<div id="uc_dialog_edit_category"  title="<?php esc_html_e("Edit Category","unlimited_elements")?>" style="display:none;" >
				
				<div class="unite-dialog-top"></div>
					
					<?php esc_html_e("Category ID", "unlimited_elements")?>: <b><span id="span_catdialog_id"></span></b>
					
					<br><br>
					
					<?php esc_html_e("Edit Title", "unlimited_elements")?>:
					<input type="text" id="uc_dialog_edit_category_title" class="unite-input-regular">
					
					<?php 
						$prefix = "uc_dialog_edit_category";
						$buttonTitle = esc_html__("Update Category", "unlimited_elements");
						$loaderTitle = esc_html__("Updating Category...", "unlimited_elements");
						$successTitle = esc_html__("Category Updated", "unlimited_elements");
						HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
					?>			
					
			</div>
		
		<?php
	}
	
	
	/**
	 * put add category dialog
	 */
	protected function putDialogAddCategory(){
		?>
		
			<div id="uc_dialog_add_category"  title="<?php esc_html_e("Add New Category","unlimited_elements")?>" style="display:none;" class="unite-inputs">
			
				<div class="unite-dialog-top"></div>
				<div class="unite-inputs-label"><?php esc_html_e("Enter Category Name", "unlimited_elements")?></div>
			
				<input id="uc_dialog_add_category_catname" type="text" class="unite-input-regular" value="">
				
			<?php 
				$prefix = "uc_dialog_add_category";
				$buttonTitle = esc_html__("Create Category", "unlimited_elements");
				$loaderTitle = esc_html__("Adding Category...", "unlimited_elements");
				$successTitle = esc_html__("Category Added", "unlimited_elements");
				HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
			?>			
				
			</div>
		
		<?php 
	}
	
	
	/**
	 * put add category dialog
	 */
	protected function putDialogDeleteCategory(){
		?>
			<div id="uc_dialog_delete_category"  title="<?php esc_html_e("Delete Category","unlimited_elements")?>" style="display:none;" class="unite-inputs">
			
				<div class="unite-dialog-top"></div>
			
				<?php esc_html_e("Do you sure to delete the: ")?>
				
				<b><span id="uc_dialog_delete_category_catname"></span></b>
				
				<?php esc_html_e(" category and all it's addons?")?>
				
			<?php 
				$prefix = "uc_dialog_delete_category";
				$buttonTitle = esc_html__("Delete Category", "unlimited_elements");
				$loaderTitle = esc_html__("Deleting Category...", "unlimited_elements");
				$successTitle = esc_html__("Category and it's addons Deleted", "unlimited_elements");
				HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
			?>			
			
			</div>
			
		<?php 
	}
	
	
	/**
	 * get category menu
	 */
	protected function getMenuCategory(){
	
		$arrMenuCat = array();
		$arrMenuCat["no_action"] = esc_html__("No Action","unlimited_elements");
	
		return($arrMenuCat);
	}
	
	
	/**
	 * put some right menu
	 */
	private function putRightMenu($arrMenu, $menuID, $menuType){
		
		?>
		
			<!-- Right menu <?php echo esc_html($menuType)?> -->
			<ul id="<?php echo esc_attr($menuID)?>" class="unite-context-menu" data-type="<?php echo esc_attr($menuType)?>" style="display:none">
			<?php foreach($arrMenu as $operation=>$text):
				$class = "";
				if(is_array($text)){
					$arr = $text;
					$text = $arr["text"];
					$class = UniteFunctionsUC::getVal($arr, "class");
				}
				
				if(!empty($class)){
					$class = esc_attr($class);
					$class = "class='$class'";
				}
			?>
			<li>
				<a href="javascript:void(0)" data-operation="<?php echo esc_attr($operation)?>" <?php echo UniteProviderFunctionsUC::escAddParam($class)?>><?php echo esc_html($text)?></a>
			</li>
			<?php endforeach?>
			</ul>
		
		<?php 
	}
	
	
	/**
	 * put right menu category
	 */
	private function putMenuCategory(){
	
		//init category menu
		$arrMenuCat = $this->getMenuCategory();
		
		$this->putRightMenu($arrMenuCat, "rightmenu_cat", "category");
	}
	
	
	/**
	 * put right menu category field
	 */
	private function putMenuCatField(){
	
		//init category field menu
		$arrMenuCatField = array();
		$arrMenuCatField["add_category"] = __("Add Category","unlimited_elements");
		
		$this->putRightMenu($arrMenuCatField, "rightmenu_catfield", "category_field");
		
	}
	
	
	/**
	 * put categories related items
	 */
	protected function putCatRelatedItems(){
		
		$this->putMenuCopyMove();
		$this->putMenuCategory();
		$this->putMenuCatField();
		$this->putDialogEditCategory();
		$this->putDialogAddCategory();
		$this->putDialogDeleteCategory();
		
	}
	
	
	protected function a_MAIN_FUNCTIONS(){}
	
	
	/**
	 * validate inited function
	 */
	private function validateInited(){
		
		if(empty($this->type))
			UniteFunctionsUC::throwError("The manager is not inited");
	}
	
	/**
	 * function for override
	 */
	protected function putInitItems(){} 
	
	
	/**
	 * function for override
	 */
	protected function putListWrapperContent(){}
	
	
	/**
	 * put items wrapper html
	 */
	private function putItemsWrapper(){
		
		$addClass = "";
		if(!empty($this->viewType)){
			$addClass = " listitems-view-".esc_attr($this->viewType);
		}
		
		$listClass = "uc-listitems-".$this->type;
		if(!empty($this->listClassType))
			$listClass = "uc-listitems-".$this->listClassType;
		
		
		?>
						<div class="items_wrapper unselectable">
						 								
						 	<div id="manager_buttons" class="manager_buttons">
						 		
						 		<?php $this->putItemsButtons()?>
						 		
						 	</div>
						 	
						 	<hr>
						 	
						 	<?php $this->putItemsFilters()?>
						 	
						 	<div id="items_outer" class="items_outer">
						 		
								<div id="items_list_wrapper" class="items_list_wrapper unselectable">
									<div id="items_loader" class="items_loader" style="display:none;">
										<?php echo esc_html($this->itemsLoaderText)?>...
									</div>
									
									<div id="no_items_text" class="no_items_text" style="display:none;">
										<?php echo UniteProviderFunctionsUC::escCombinedHtml($this->getNoItemsText())?>
									</div>
									
									<?php $this->putListWrapperContent()?>
									
									<ul id="uc_list_items" class="list_items unselectable <?php echo esc_attr($listClass)?> <?php echo esc_attr($addClass)?>"><?php $this->putInitItems()?></ul>
									<div id="drag_indicator" class="drag_indicator" style="display:none;"></div>
									<div id="shadow_bar" class="shadow_bar" style="display:none"></div>
									<div id="select_bar" class="select_bar" style="display:none"></div>
								</div>
							
							</div>								
						</div>

		<?php 
	}
	
	
	/**
	 * get html categories select
	 */
	protected function getHtmlSelectCats(){
		
		echo("getHtmlSelectCats: function for override");
		exit();
	}
	
	
	/**
	 * html status operations html
	 */
	private function putStatusLineOperations(){
		
		?>
		
							<div class="status_operations">
								<div class="status_num_selected">
									<span id="num_items_selected">0</span> <?php echo esc_attr($this->textItemsSelected)?>
								</div>
								
								<?php if($this->hasCats == true): 
									$htmlCatSelect = $this->getHtmlSelectCats();
								?>
								
								<div id="item_operations_wrapper" class="item_operations_wrapper unite-disabled">
									
									<?php esc_html_e("Move To", "unlimited_elements")?>
									
									<select id="select_item_category" disabled="disabled">
										<?php echo UniteProviderFunctionsUC::escCombinedHtml($htmlCatSelect) ?>
									</select>				
									 
									 <a id="button_items_operation" class="unite-button-secondary button-disabled" href="javascript:void(0)">GO</a>
								 </div>
								 
								 <?php endif?>
								 
							</div>
		
		<?php 
		
	}
	
	
	/**
	 * put status line html
	 */
	private function putStatusLine(){
		
		?>
						<div class="status_line">
													
			<?php 
				if($this->enableStatusLineOperations == true)
					$this->putStatusLineOperations();
			?>
						<div class="status_loader_wrapper">
							<div id="status_loader" class="status_loader" style="display:none;"></div>
						</div>
			
						<div class="status_text_wrapper">
							<span id="status_text" class="status_text" style="display:none;"></span>
						</div>
							
						</div>
		<?php 
	}
	
	/**
	 * put copy move menu
	 */
	private function putMenuCopyMove(){
		?>
			<ul id="menu_copymove" class="unite-context-menu" style="display:none">
				<li>
					<a href="javascript:void(0)" data-operation="copymove_move"><?php esc_html_e("Move Here","unlimited_elements")?></a>
				</li>
			</ul>
		<?php
	}
	
	
	
	/**
	 * put single item menu
	 */
	private function putMenuSingleItem(){
		
		$arrMenuItem = $this->getMenuSingleItem();
		
		if(!is_array($arrMenuItem))
			$arrMenuItem = array();
		
		$this->putRightMenu($arrMenuItem, "rightmenu_item", "single_item");
		
	}
	
	
	/**
	 * get multiple items menu
	 */
	protected function getMenuMulitipleItems(){
		$arrMenuItemMultiple = array();
		$arrMenuItemMultiple["no_action"] = __("No Action","unlimited_elements");
		return($arrMenuItemMultiple);
	}
	
	
	/**
	 * put multiple items menu
	 */
	private function putMenuMultipleItems(){
		
		$arrMenuItemMultiple = $this->getMenuMulitipleItems();
		
		?>
			<!-- Right menu multiple -->
			
			<ul id="rightmenu_item_multiple" class="unite-context-menu" style="display:none">
				<?php foreach($arrMenuItemMultiple as $operation=>$text):?>
				<li>
					<a href="javascript:void(0)" data-operation="<?php echo esc_attr($operation)?>"><?php echo esc_html($text)?></a>
				</li>
				<?php endforeach?>
			</ul>
		
		<?php
	}
	
	
	/**
	 * put right menu field
	 */
	private function putMenuField(){
		
		$arrMenuField = $this->getMenuField();
		
		
		?>
			<!-- Right menu field -->
			<ul id="rightmenu_field" class="unite-context-menu" style="display:none">
				<?php foreach($arrMenuField as $operation=>$text):?>
				<li>
					<a href="javascript:void(0)" data-operation="<?php echo esc_attr($operation)?>"><?php echo esc_html($text)?></a>
				</li>
				<?php endforeach?>			
			</ul>
		
		<?php
	}
	
	
	/**
	 * set view type
	 */
	public function setViewType($viewType){
		$this->viewType = $viewType;
	}
	
	
	/**
	 * get manager name
	 */
	public function getManagerName(){
		
		return($this->managerName);
	}
	
	/**
	 * set manager name
	 */
	public function setManagerName($name){
		
		$this->managerName = $name;
	}
	
	/**
	 * add the pass data to js / php interface
	 */
	public function addPassData($key, $value){
		
		if(empty($this->arrPassData))
			$this->arrPassData = array();
		
		
		$this->arrPassData[$key] = $value;
	}
	
	
	
	/**
	* put scripts according manager type
	 */
	public static function putScriptsIncludes($type){
		
		
		HelperUC::addScript("dropzone", "dropzone_js","js/dropzone");
		HelperUC::addStyle("dropzone", "dropzone_css","js/dropzone");
		
		HelperUC::addScript("unitecreator_manager_items","unitecreator_manager_items","js/manager");
		HelperUC::addScript("unitecreator_manager","unitecreator_manager","js/manager");
		HelperUC::addStyle("unitecreator_manager","unitecreator_manager_css");
		
		switch($type){
			case self::TYPE_PAGES:
			case self::TYPE_ADDONS:
				HelperUC::addScript("unitecreator_manager_cats","unitecreator_manager_cats","js/manager");
				HelperUC::addScript("unitecreator_manager_actions_addons","unitecreator_manager_actions_addons","js/manager");
				HelperUC::addScript("unitecreator_browser","unitecreator_browser");
				HelperUC::addStyle("unitecreator_browser","unitecreator_browser_css");
			break;
			case self::TYPE_ITEMS_INLINE:
				HelperUC::addScript("unitecreator_params_dialog", "unitecreator_params_dialog");
				HelperUC::addScript("unitecreator_manager_actions_inline","unitecreator_manager_actions_inline","js/manager");
			break;
		}
		
	}
	
	/**
	 * call it before put html, function for override
	 */
	protected function onBeforePutHtml(){}
	
	
	/**
	 * output manager html
	 */
	public function outputHtml(){
		
		$this->validateInited();
		
		$this->onBeforePutHtml();
		
		$addClass = "";
		if($this->hasCats == false)
			$addClass = " uc-nocats ";
		
		$managerClass = "uc-manager-".$this->type;
		
		$htmlPassData = "";
		if(!empty($this->arrPassData))
			$htmlPassData = UniteFunctionsUC::jsonEncodeForHtmlData($this->arrPassData,"passdata");
		
		//add text
		if(!empty($this->arrText)){
			$optionText = UniteFunctionsUC::jsonEncodeForHtmlData($this->arrText, "text");
			if(!empty($optionText))
				$this->managerAddHtml .= " ".$optionText;
		}
		
		if(!empty($this->arrOptions)){
			$optionOptions = UniteFunctionsUC::jsonEncodeForHtmlData($this->arrOptions, "options");
			if(!empty($optionOptions))
				$this->managerAddHtml .= " ".$optionOptions;
		}
		
			
		try{
		
		?>
		
		<div id="uc_managerw" class="uc-manager-outer <?php echo esc_attr($managerClass)?>" data-managername="<?php echo esc_attr($this->managerName)?>" data-type="<?php echo esc_attr($this->type)?>" <?php echo UniteProviderFunctionsUC::escAddParam($htmlPassData)?> <?php echo UniteProviderFunctionsUC::escAddParam($this->managerAddHtml)?>>
			
			<div class="manager_wrapper <?php echo esc_attr($addClass)?> unselectable" >
				
				<?php if($this->hasCats == true): ?>
			
				<table class="layout_table" width="100%" cellpadding="0" cellspacing="0">
					
					<tr>
						<td class="cell_cats" width="220px" valign="top">
							<?php $this->putHtmlCats()?>
						</td>
						
						<td class="cell_items" valign="top">
													
							<?php $this->putItemsWrapper()?>
							
						</td>
					</tr>
					<tr>
						<td colspan="2">
							
							<?php $this->putStatusLine() ?>
							
						</td>
					</tr>
					
				</table>
	
				<?php else:?>
					
					<?php 
						$this->putItemsWrapper();
						$this->putStatusLine();
					?>
					
					
				<?php endif?>
				
			</div>	<!--  end manager wrapper -->
		
			<div id="manager_shadow_overlay" class="manager_shadow_overlay" style="display:none"></div>
		
			<?php 

			
				$this->putMenuSingleItem();
				$this->putMenuMultipleItems();
				$this->putMenuField();
				
				if($this->hasCats)
					$this->putCatRelatedItems();
				
				$this->putAddHtml();
				
			?>
			
			</div>
			<?php 
			
			}catch(Exception $e){
				$message = "<br><br>manager error: <b>".$e->getMessage()."</b>";
				
				echo "</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='unite-color-red'>".esc_html($message)."</div>";
				
				if(GlobalsUC::SHOW_TRACE == true)
					dmp($e->getTraceAsString());
			}
			
	}
	
	
	/**
	 * init manager
	 */
	public function init($type = ""){
		
		$this->beforeInit($type);
		
		//the type should be set already in child classes
		$this->validateInited();
		
		$this->itemsLoaderText = __("Getting Items", "unlimited_elements");
		$this->textItemsSelected = __("items selected","unlimited_elements");
		
		if($this->hasCats){
			$this->objCats = new UniteCreatorCategories();
			$this->selectedCategory = "";
		}
		
		$this->afterInit($type);
	
	}
	
}