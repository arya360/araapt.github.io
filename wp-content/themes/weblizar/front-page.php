<?php 
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage weblizar
 */
get_header();
 $wl_theme_options = weblizar_get_options(); 
if($wl_theme_options['_frontpage']=="on" && is_front_page()) { ?>
<!-- Carousel
    ================================================== --> 
		<?php if(get_theme_mod("weblizar_slider",$wl_theme_options['weblizar_slider'])=='1'){
			get_template_part('home','slider1');
		} else {
			get_template_part('home','slider2');
		} ?>
		<div class="content-wrapper">    
			<div class="body-wrapper">
			<?php 
			if($sections = json_decode(get_theme_mod('home_reorder'),true)) {
				  foreach ($sections as $section) {
					$data =$section.'_home';
					if($wl_theme_options[$data]=="on") {
					get_template_part('home', $section);
					}
				}
			} else {
				if($wl_theme_options['service_home']=='on') {
				get_template_part('home','service'); 
				}
				if($wl_theme_options['blog_home']=='on') {
				get_template_part('home','blog');
				}
			} ?>			
			</div>
		</div><!--.content-wrapper end -->
		<style>
			ul.post-footer {
				text-align: center;
				list-style: none;
				margin-top: 50px;
			}
			.item {
			  margin-bottom: 30px;
			}
			a.append-button.btn.btn-color{
				background-color: #3498db;
				border: 1px solid transparent;
				color: #fff;
				font-size: 21px;
				border-radius: 6px;
				line-height: 1.4;
			}
			a.append-button.btn.btn-color:hover {
			  opacity: 0.9;
			  color: #fff;
			}
		</style>
<?php get_footer(); 
}
else 
if(is_page()) { 
get_template_part('page'); 
} else { 
get_template_part('index'); 
}	
?>