<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

HelperHtmlUC::putAddonTypesBrowserDialogs();

$settings = new UniteCreatorSettings();

$settings->addAddonPicker("addon_devider", "", "Select Divider",array("addontype" => GlobalsUC::ADDON_TYPE_SHAPE_DEVIDER));
$settings->addAddonPicker("addon", "", "Select Addon");


$output = new UniteSettingsOutputWideUC();

$output->init($settings);
$output->draw("settings_test");

?>
<script>

jQuery("document").ready(function(){
	var settings = new UniteSettingsUC();
	var objSettings = jQuery("#unite_settings_wide_output_1");
	settings.init(objSettings);
	
});

</script>
<?php 