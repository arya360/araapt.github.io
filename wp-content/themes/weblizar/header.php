<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">``
	<![endif]-->
	<?php $wl_theme_options = weblizar_get_options(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />  
    <meta charset="<?php bloginfo('charset'); ?>" />	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<div id="menu_wrapper" <?php if($wl_theme_options['sticky_header']!='') { ?> data-spy="affix" data-offset-top="95" <?php } ?>>
	<div class="top_wrapper">
		<header id="header">
			<div class="row">
				<nav class="navbar navbar-default hd_cover" role="navigation" <?php if ( has_header_image() ) { ?> style='background-image: url("<?php header_image(); ?>")' <?php  } ?>>
					<div class="container-fluid">
						<div class="container fix-menu">	
							<div class="col-md-12">	
								<?php if($wl_theme_options['web_mail']!='') { ?>						
								<div class="col-md-4 col-sm-12 col-xs-6 web-email">
									<i class="fa fa-envelope"></i> <?php echo esc_html($wl_theme_options['web_mail']); ?>	
								</div>
								<?php } if($wl_theme_options['web_phone']!='') { ?>						
								<div class="col-md-4 col-sm-12 col-xs-6 web-email">
									<i class="fa fa-phone"></i> <?php echo esc_html($wl_theme_options['web_phone']); ?>	
								</div>
								<?php } if($wl_theme_options['search_form']!=''){ ?>
								<div class="col-md-4 col-sm-12 col-xs-12 top-search-form">
								<?php echo get_search_form(); ?>	
								</div>	
								<?php } ?>	
							</div>							
							<div class="col-md-4 col-sm-12 col-xs-12">
								<div class="navbar-header" id="navbar-header">						  
								  <div class="logo pull-left">							
									<a title="Weblizar" href="<?php echo esc_url(home_url( '/' )); ?>">
									<?php $custom_logo_id = get_theme_mod( 'custom_logo' );
									$image = wp_get_attachment_image_src( $custom_logo_id,'full' ); 
									if(has_custom_logo()) { ?>
									<span class="site-custom_logo"></span>
									<img  src="<?php echo esc_attr($image[0]); ?>" style="height:<?php if($wl_theme_options['height']!='') { echo esc_attr($wl_theme_options['height']); }  else { "55"; } ?>px; width:<?php if($wl_theme_options['width']!='') { echo esc_attr($wl_theme_options['width']); }  else { "150"; } ?>px;" />
									<?php } else { echo '<span class="site-title">'.esc_html(get_bloginfo()).'</span>'; } ?>
									</a>
									<p class="site-description"><?php bloginfo( 'description' ); ?></p>
								  </div>
								  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only"><?php esc_html_e('Toggle navigation','weblizar'); ?></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								  </button> 
								</div>
							</div>
							<div class="col-md-8 col-sm-12 col-xs-12 header-right">
								<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								  <?php wp_nav_menu( array(
									'theme_location'    => 'primary',               
									'container'         => 'nav-collapse collapse navbar-inverse-collapse',							
									'menu_class'        => 'nav navbar-nav navbar-left',
									'fallback_cb'       => 'weblizar_fallback_page_menu',
									'walker'            => new wp_bootstrap_navwalker())
									);  ?>
								</div>
							</div>
						</div>
					</div>
				</nav>		
			</div>
		</header>
	</div>
</div>
<script>
jQuery(document).ready(function() {
	jQuery(window).scroll(function () {
	if( jQuery(window).width() > 768) {
		if (jQuery(this).scrollTop() > 100) {
		jQuery('header').addClass('sticky-head');
		}
		else {
	jQuery('header').removeClass('sticky-head');
	}
	}
		else {
		if (jQuery(this).scrollTop() > 100) {
			jQuery('header').addClass('sticky-head');
		}else {
	jQuery('header').removeClass('sticky-head');
	}
		}				
	});
	});
</script>