<?php $wl_theme_options = weblizar_get_options(); ?>
<div class="guardian_options_slider">
		<div class="swiper-container top_slider">
			<div class="swiper-wrapper ">
				<?php if($wl_theme_options['slide_image'] !='') { ?>			
				<div class="swiper-slide">
					<img src="<?php echo esc_url($wl_theme_options['slide_image']); ?>" alt="<?php  the_title(); ?>" class="home_slider img-responsive" />
					<div class="overlay"></div>
						<div class="carousel-caption">	
						<?php if($wl_theme_options['slide_title'] !='') { ?> <p class="guardian_slide_title animation animated-item-1"><strong><?php echo  esc_attr($wl_theme_options['slide_title']); ?></strong></p>	<?php } ?>
						<?php if($wl_theme_options['slide_desc'] !='') { ?>
						<p class="guardian_slide_desc animation animated-item-2"><?php echo  html_entity_decode(get_theme_mod('slide_desc' , $wl_theme_options['slide_desc'])); ?></p>
						<?php } ?>
						<?php if($wl_theme_options['slide_desc'] !='') { ?>
						<p class="slider-btn"><a class="btn btn-lg btn-primary animation animated-item-3" target="_blank" href="<?php if($wl_theme_options['slide_btn_link'] !='') { echo esc_url($wl_theme_options['slide_btn_link']); }  ?>" role="button"><?php echo esc_attr($wl_theme_options['slide_btn_text']); ?></a></p>
						<?php } ?>
						</div>
				</div> 
				<?php } ?>
				<?php if($wl_theme_options['slide_image_1'] !='') { ?>			
				<div class="swiper-slide">
					<img src="<?php echo esc_url($wl_theme_options['slide_image_1']); ?>" alt="<?php  the_title(); ?>" class="home_slider img-responsive" />
					<div class="overlay"></div>
					<div class="container">
						<div class="carousel-caption">	
						<?php if($wl_theme_options['slide_title_1'] !='') { ?> <p class="guardian_slide_title animation animated-item-1"><strong><?php echo  esc_attr($wl_theme_options['slide_title_1']); ?></strong></p>	<?php } ?>
						<?php if($wl_theme_options['slide_desc_1'] !='') { ?>
						<p class="animation animated-item-2 guardian_slide_desc "><?php echo  html_entity_decode(get_theme_mod('slide_desc_1' , $wl_theme_options['slide_desc_1'])); ?></p>
						<?php } ?>
						<?php if($wl_theme_options['slide_desc_1'] !='') { ?>
						<p class="slider-btn"><a class="btn btn-lg btn-primary animation animated-item-3" target="_blank" href="<?php if($wl_theme_options['slide_btn_link_1'] !='') { echo esc_url($wl_theme_options['slide_btn_link_1']); }  ?>" role="button"><?php echo esc_attr($wl_theme_options['slide_btn_text']); ?></a></p>
						<?php } ?>
						</div>
					</div>
				</div> 
				<?php } ?>
				<?php if($wl_theme_options['slide_image_2'] !='') { ?>			
				<div class="swiper-slide">
					<img src="<?php echo esc_url($wl_theme_options['slide_image_2']); ?>" alt="<?php  the_title(); ?>" class="home_slider img-responsive" />
					<div class="overlay"></div>
					<div class="container">
						<div class="carousel-caption">	
						<?php if($wl_theme_options['slide_title_2'] !='') { ?> <p class="guardian_slide_title animation animated-item-1"><strong><?php echo  esc_attr($wl_theme_options['slide_title_2']); ?></strong></p>	<?php } ?>
						<?php if($wl_theme_options['slide_desc_2'] !='') { ?>
						<p class="guardian_slide_desc animation animated-item-2"><?php echo  html_entity_decode(get_theme_mod('slide_desc_2' , $wl_theme_options['slide_desc_2'])); ?></p>
						<?php } ?>
						<?php if($wl_theme_options['slide_desc_2'] !='') { ?>
						<p class="slider-btn"><a class="btn btn-lg btn-primary animation animated-item-3" target="_blank" href="<?php if($wl_theme_options['slide_btn_link_2'] !='') { echo esc_url($wl_theme_options['slide_btn_link_2']); }  ?>" role="button"><?php echo esc_attr($wl_theme_options['slide_btn_text']); ?></a></p>
						<?php } ?>
						</div>
					</div>
				</div> 
				<?php } ?>
			</div>
			<!-- Add Arrows -->
			<div class="swiper-button-prev swiper-button-prev5 swiper-button-white"></div>
			<div class="swiper-button-next swiper-button-next5 swiper-button-white"></div>
		</div>	
		<script>
			var swiper = new Swiper('.top_slider', {
			spaceBetween: 30,
			loop:true,
			autoplay: {
			delay: <?php echo esc_attr($wl_theme_options['slider_image_speed']); ?>,
			disableOnInteraction: false,
			},
			navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
			},
			});
		</script>
	</div>
	<style type="text/css">
		.guardian_options_slider .home_slider
		{
			width: 100%;
		}
	</style>