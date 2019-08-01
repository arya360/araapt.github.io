<?php if (!function_exists('weblizar_home_page')) {
	function weblizar_home_page() {
	$page2=add_theme_page(__('Welcome to Weblizar', 'weblizar'), __('How To Set Homepage', 'weblizar'), 'edit_theme_options', 'weblizarhome', 'weblizar_display_theme_home_page');
	
	add_action('admin_print_styles-'.$page2, 'weblizar_admin_infohome');
	}	
}
add_action('admin_menu', 'weblizar_home_page');

function weblizar_admin_infohome(){
	// CSS
	wp_enqueue_style('bootstrap',  get_template_directory_uri() .'/core/admin/bootstrap/css/bootstrap.min.css');
	wp_enqueue_style('admin',  get_template_directory_uri() .'/core/admin/admin.css');
	wp_enqueue_style('font-awesome',get_template_directory_uri() . '/css/font-awesome-4.7.0/css/font-awesome.min.css');

	//JS
	wp_enqueue_script('bootstrap-js', get_template_directory_uri().'/core/admin/bootstrap/js/bootstrap.min.js');
	wp_enqueue_script('script-menu', get_template_directory_uri().'/assets/js/script.js');
} 
if (!function_exists('weblizar_display_theme_home_page')) {
	function weblizar_display_theme_home_page() {
		$theme_data = wp_get_theme(); ?>		
<div class="wrapper">
<!-- Header -->
<header>
<div id="snow"></div>
<div class="container-fluid p_header">
	<div class="">
		<div class="row p_head">
		<h2> Weblizar Documentation </h2>
			<div class="col-md-8">
			<p>Weblizar is a powerful and versatile WordPress theme with pixel perfect design and outstanding functionality. Weblizar offers a fullscreen featured image slideshow built using cutting-edge technology, lightweight assets, and offers featured image support. You can use weblizar theme for your business, blogging, hotel, doctors, freelancer resume, restaurant or any type of large or small business website. It has 4 page layouts, 2 page templates, It has five widgets available (one sidebar, four footers), and using the sidebar widget also enables you to make a two-column design.</p>
			<p>In the footer section ,theme offers Social Media Links to add your Social Links here. Weblziar focusing on business template, awesome Caroufredsel slider, Powerful but simple Theme Options for full CMS control option to match your logo & website, wide layout, light & dark color skin, translation ready and many more.</p>
			<a target="_blank"class="view-demo" href="https://wp-themes.com/weblizar/"> <i class="fa fa-eye"></i> View Demo </a>
			</div>
			<div class="col-md-4"><img src="<?php echo esc_url(get_template_directory_uri()) ?>/images/Weblizar.jpg" class="img-responsive" alt=""/></div>	
		</div>
	</div>
</div>
<div class="container-fluid home-page">
<h3> Lets Proceed.... </h3>
	<div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><i class="fa fa-chevron-down"></i> How to setup Homepage?</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in">
        <div class="panel-body"><h4>1. Firstly make your static page(home page).<img class="imgheathcare" src="<?php echo esc_url(get_template_directory_uri()) ?>/images/image1.jpg" class="img-responsive" alt=""/></h4>
			<h4>2. Go to Customizer -> Homepage Settings -> see the given image - </h4>
			<h4>3. Now go to Weblizar Options -> Theme General Options -> Select Show Front Page option</h4>
			<h4>4. Save & Publish. </h4>
			</div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><i class="fa fa-chevron-down"></i> How to setup a Slider on theme?</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">
        <div class="panel-body"><h4>1. Go to Appearance -> Customizer. <img class="imgheathcare" src="<?php echo esc_url(get_template_directory_uri()) ?>/images/image2.jpg" class="img-responsive" alt=""/></h4>
			<h4>2. Now go to Weblizar Options -> Theme Slider Options -> see the given image - </h4>
			<h4>3. Save & Publish.</h4></div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><i class="fa fa-chevron-down"></i> How to setup a Service section on theme?</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse">
        <div class="panel-body"><h4>1. Go to Weblizar Options -> Service Options -> see the given image - <img class="imgheathcare" src="<?php echo esc_url(get_template_directory_uri()) ?>/images/image3.jpg" class="img-responsive" alt=""/></h4>
			<h4>2. Save & Publish.</h4></div>
      </div>
    </div>
	 <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse4"><i class="fa fa-chevron-down"></i> How to setup Footer text and link?</a>
        </h4>
      </div>
      <div id="collapse4" class="panel-collapse collapse">
        <div class="panel-body"><h4>1. Go to Appearance -> Customizer.</h4>
			<h4>2. Now go to Weblizar Options -> Footer Options -> you can add text and link. </h4>
			<h4>3. Save & Publish.</h4></div>
      </div>
    </div>
	 <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse5"><i class="fa fa-chevron-down"></i> How to add Social icons?</a>
        </h4>
      </div>
      <div id="collapse5" class="panel-collapse collapse">
        <div class="panel-body"><h4>1. Go to Appearance -> Customizer.</h4>
			<h4>2. Now go to Weblizar Options -> Social Options -> here you can add social icons.</h4>
			<h4>3. Save & Publish.</h4></div>
      </div>
    </div>
	 <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse6"><i class="fa fa-chevron-down"></i> How to change layout of homepage?</a>
        </h4>
      </div>
      <div id="collapse6" class="panel-collapse collapse">
        <div class="panel-body"><h4>1. Go to Appearance -> Customizer.</h4>
			<h4>2. Now go to Weblizar Options -> Home Page Layout Options </h4>
			<h4>3. Save & Publish.</h4></div>
      </div>
    </div>
  </div> 
</div>
<!-- Header -->
</div>
<?php
	}
}
?>