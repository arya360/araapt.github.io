<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorActivationView extends UniteElementsBaseUC{

	const CODE_TYPE_ACTIVATION = "activation";
	const CODE_TYPE_ENVATO = "envato";
	const CODE_TYPE_FREEMIUS = "freemius";
	
	protected $urlPricing;
	protected $urlSupport;
	protected $textGoPro, $textAndTemplates, $textPasteActivationKey, $textPlaceholder;
	protected $textLinkToBuy, $textDontHave, $textActivationFailed, $textActivationCode;
	protected $codeType = self::CODE_TYPE_ACTIVATION;
	protected $product;
	protected $isExpireEnabled = true, $textSwitchTo;
	protected $writeRefreshPageMessage = true;
	protected $textDontHaveLogin, $textLinkToLogin, $urlLogin;
	protected $textUnleash, $textActivate, $textYourProAccountLifetime;
	protected $simpleButtonMode = false;
	
	
	/**
	 * init the variables
	 */
	public function __construct(){
		
		$this->urlPricing = GlobalsUC::URL_BUY;
		$this->urlSupport = GlobalsUC::URL_SUPPORT;
		
		$this->textActivate = esc_html__("Activate Blox Pro", "unlimited_elements");
		
		$this->textGoPro = esc_html__("GO PRO", "unlimited_elements");
		$this->textUnleash = esc_html__("Unleash access to +700 addons,", "unlimited_elements");
		
		$this->textAndTemplates = esc_html__("+100 page templates and +50 section designs", "unlimited_elements");
		
		$this->textPasteActivationKey = esc_html__("Paste your activation key here", "unlimited_elements");
		
		$this->textPlaceholder = "xxxx-xxxx-xxxx-xxxx";
		$this->textLinkToBuy = esc_html__("View our pricing plans", "unlimited_elements");
		
		$this->textDontHave = esc_html__("Don't have a pro activation key?", "unlimited_elements");

		$this->textDontHaveLogin = esc_html__("If you already purchased, get the key from my account?", "unlimited_elements");
		$this->textLinkToLogin = esc_html__("Go to My Account", "unlimited_elements");
		$this->urlLogin = "http://my.unitecms.net";
		
		$this->textActivationFailed = esc_html__("You probably got your activation code wrong", "unlimited_elements");
		
		$this->textYourProAccountLifetime = esc_html__("Your pro account is activated lifetime for this site", "unlimited_elements");
		
	}
	
	
	/**
	 * put pending activation html
	 */
	public function putPendingHTML(){
		?>
		You are using free version of <b>Unlimited Elements</b>. The pro version will be available for sale in codecanyon.net within 5 days.
		<br>
		<br>
		Please follow the plugin updates, and the pro version activation will be revealed.
		<br>
		<br>
		For any quesiton you can turn to: <b>support@blox-builder.com</b>
		<?php 
	}
	
	/**
	 * put popup form
	 */
	protected  function putPopupForm(){
		?>
             <label><?php echo esc_html($this->textPasteActivationKey)?>:</label>
              
              <input id="uc_activate_pro_code" type="text" placeholder="<?php echo esc_attr($this->textPlaceholder)?>" value="">
                                
              <div class="uc-activation-section-wrapper">
                                
	              <input id="uc_button_activate_pro" type="button" class='uc-button-activate' data-codetype="<?php echo esc_attr($this->codeType)?>" data-product="<?php echo esc_attr($this->product)?>" value="<?php echo esc_attr($this->textActivate)?>">
                                
                   <div id="uc_loader_activate_pro" class="uc-loader-activation" style='display:none'>
					
						<span class='loader_text'>	                                	
	                                		<?php esc_html_e("Activating", "unlimited_elements")?>...
	                    </span>
	                   
	               </div>
	                                
               </div>
		
		<?php 
	}
	
	/**
	 * put activation html
	 */
	public function putActivationHtml(){
		
		?>
		   <div class="uc-activation-view">
		   	   
	           <div class="uc-popup-container uc-start">
	                <div class="uc-popup-content">
	                    <div class="uc-popup-holder">
	                        <div class="xlarge-title"><?php echo esc_html($this->textGoPro)?></div>
	                        
	                        <div class="popup-text"><?php echo esc_html($this->textUnleash)?><br> <?php echo esc_html($this->textAndTemplates)?></div>
	                        <div class="popup-form">
	                        		
	                            <?php $this->putPopupForm()?>
	                                
	                        </div>
	                        
	                        <div class="bottom-text">
	                        	<?php echo $this->textDontHave?>
	                        	<br>
	                        	<a href="<?php echo esc_attr($this->urlPricing)?>" target="_blank" class="blue-text"><?php echo esc_html($this->textLinkToBuy)?></a>
	                        </div>
	                        
	                        <?php if(!empty($this->textDontHaveLogin)):?>
	                        
	                        <div class="bottom-text">
	                        	<?php echo esc_html($this->textDontHaveLogin)?>
	                        	<br>
	                        	<a href="<?php echo esc_attr($this->urlLogin)?>" target="_blank" class="blue-text"><?php echo esc_html($this->textLinkToLogin)?></a>
	                        </div>
	                        
	                        <?php endif?>
	                        
							<?php if(!empty($this->textSwitchTo)):?>
	                        <div class="bottom-text">
	                        	<?php echo $this->textSwitchTo?><br>
	                        </div>
	                        <?php endif?>
	                        
	                	</div>
	            	</div>
	            </div>
	            
	            <!-- failed dialog -->
	            
	            <div class="uc-popup-container uc-fail hidden">
	                <div class="uc-popup-content">
	                    <div class="uc-popup-holder">
	                        <div class="large-title"><?php esc_html_e("Ooops", "unlimited_elements")?>.... <br><?php esc_html_e("Activation Failed", "unlimited_elements")?> :(</div>
	                        <div class="popup-error"></div>
	                        <div class="popup-text"><?php echo esc_html($this->textActivationFailed)?> <br>to try again <a id="activation_link_try_again" href="javascript:void(0)">click here</a></div>
	                        <div class="bottom-text"><?php esc_html_e("or contact our","unlimited_elements")?> <a href="<?php echo esc_attr($this->urlSupport)?>" target="_blank"><?php esc_html_e("support center", "unlimited_elements")?></a></div>
	                    </div>
	                </div>
	            </div>
	            
	            <!-- activated dialog -->
	            
	            <div class="uc-popup-container uc-activated hidden">
	                <div class="uc-popup-content">
	                    <div class="uc-popup-holder">
	                        <div class="xlarge-title"><?php esc_html_e("Hi Five", "unlimited_elements")?>!</div>
	                        
	                        <?php if($this->isExpireEnabled == true):?>
	                        	<div class="popup-text small-padding"><?php echo esc_html($this->textYourProAccountLifetime)?></div>
		                        <div class="days"></div>
		                        <span><?php esc_html_e("DAYS", "unlimited_elements")?></span>
		                        <br><br>
		                        
		                        <?php if($this->writeRefreshPageMessage == true):?>
		                        <a href="javascript:location.reload()" class="btn"><?php esc_html_e("Refresh page to View Your Pro Catalog", "unlimited_elements")?></a>
		                        <?php endif?>
		                        
	                        <?php else:?>
	                        	
	                        	<div class="popup-text small-padding"><?php esc_html_e("Your pro account is activated lifetime for this site","unlimited_elements")?>!</div>
		                       	
	                        	<div class="popup-text small-padding"><?php esc_html_e("Thank you for purchasing from us and good luck", "unlimited_elements")?>!</div>
	                        	
	                        <?php endif?>
	                        
	                    </div>
	                </div>
	            </div>
		</div>
		
		<?php 
	}
	
	/**
	 * put deactivate html
	 */
	public function putHtmlDeactivate(){
		
		?>
		<h2><?php esc_html_e("This pro version is active!", "unlimited_elements")?></h2>
		
		<a href="javascript:void(0)" class="uc-link-deactivate unite-button-primary" data-product="<?php echo esc_attr($this->product)?>"><?php esc_html_e("Deactivate Pro Version", "unlimited_elements")?></a>
		
		<?php 
	}
	
	
	/**
	 * put initing JS
	 */
	public function putJSInit(){
		?>
		
		<script>

		jQuery("document").ready(function(){

			if(!g_ucAdmin)
				var g_ucAdmin = new UniteAdminUC();
			
			g_ucAdmin.initActivationDialog(true);
			
			
		});
		
		</script>
		
		<?php 
	}
	
	/**
	 * put activation HTML
	 */
	public function putHtmlPopup(){
		
		$title = esc_html__("Activate Your Pro Account", "unlimited_elements");
		
		?>
           <div class="activateProDialog" title="<?php echo esc_attr($title)?>" style="display:none">
           		
           		<?php $this->putActivationHtml(true) ?>
            	
            </div>
		
		<?php 		
	}
	
}

