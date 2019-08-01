<?php $wl_theme_options = weblizar_get_options(); 
 if($wl_theme_options['slider_image_speed']!='')
	{	
	echo '<script type="text/javascript">
		jQuery(document ).ready(function( $ ) {
		jQuery("#myCarousel" ).carousel({
			interval:'.esc_attr($wl_theme_options['slider_image_speed']).'
		    });
	   });
	</script>';
	} ?>
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>        
        <li data-target="#myCarousel" data-slide-to="2"></li>        
      </ol>
      <div class="carousel-inner">
	  <?php $wl_theme_options = weblizar_get_options();
		$ImageUrl1 = WL_TEMPLATE_DIR_URI ."/images/slide-1.jpg";
		$ImageUrl2 = WL_TEMPLATE_DIR_URI ."/images/slide-2.jpg";
		$ImageUrl3 = WL_TEMPLATE_DIR_URI ."/images/slide-3.jpg";  ?>
        <div class="item active">
			<?php if($wl_theme_options['slide_image']!='') {  ?>
          <img src="<?php echo esc_url($wl_theme_options['slide_image']); ?>" class="img-responsive" alt="First slide">
          <?php } else { ?>
		  <img src="<?php echo esc_url($ImageUrl1); ?>" class="img-responsive" alt="First slide">
		  <?php } ?>		  
		  <div class="container">
            <div class="carousel-caption">
			<?php if($wl_theme_options['slide_title']!='') {  ?>
              <h1 class="weblizar_slide_title"><?php echo esc_html($wl_theme_options['slide_title']); ?></h1>
			<?php } 	
			 if($wl_theme_options['slide_desc']!='') {  ?>
			 <p class="weblizar_slide_desc"><?php echo esc_html($wl_theme_options['slide_desc']); ?></p>
			 <?php }
				if($wl_theme_options['slide_btn_text']!='') { ?>
              <p class="weblizar_slide_btn_text"><a class="btn btn-lg btn-primary" href="<?php if($wl_theme_options['slide_btn_link']!='') { echo esc_url($wl_theme_options['slide_btn_link']); } ?>" role="button"><?php echo esc_html($wl_theme_options['slide_btn_text']); ?></a></p>
			  <?php } ?>
            </div>
          </div>
        </div>		
        <div class="item">		
			<?php if($wl_theme_options['slide_image_1']!='') {  ?>
          <img src="<?php echo esc_url($wl_theme_options['slide_image_1']); ?>" class="img-responsive" alt="Second slide">
          <?php } else { ?>
		  <img src="<?php echo esc_url($ImageUrl2); ?>" class="img-responsive" alt="Second slide">
		  <?php } ?>
          <div class="container">
            <div class="carousel-caption">
			<?php if($wl_theme_options['slide_title_1']!='') {  ?>
              <h1 class="weblizar_slide_title_1"><?php echo esc_html($wl_theme_options['slide_title_1']); ?></h1>
			<?php } 	
			 if($wl_theme_options['slide_desc_1']!='') {  ?>
			 <p class="weblizar_slide_desc_1"><?php echo esc_html($wl_theme_options['slide_desc_1']); ?></p>
			 <?php }
				if($wl_theme_options['slide_btn_text_1']!='') { ?>
              <p class="weblizar_slide_btn_text_1"><a class="btn btn-lg btn-primary" href="<?php if($wl_theme_options['slide_btn_link_1']!='') { echo esc_url($wl_theme_options['slide_btn_link_1']); } ?>" role="button"><?php echo esc_html($wl_theme_options['slide_btn_text_1']); ?></a></p>
			  <?php } ?>
            </div>
          </div>
        </div>
		<div class="item">		
			<?php if($wl_theme_options['slide_image_2']!='') {  ?>
          <img src="<?php echo esc_url($wl_theme_options['slide_image_2']); ?>" class="img-responsive" alt="Third slide">
          <?php } else { ?>
		  <img src="<?php echo esc_url($ImageUrl3); ?>" class="img-responsive" alt="Third slide">
		  <?php } ?>
          <div class="container">
            <div class="carousel-caption">
			<?php if($wl_theme_options['slide_title_2']!='') {  ?>
              <h1 class="weblizar_slide_title_2"><?php echo esc_html($wl_theme_options['slide_title_2']); ?></h1>
			<?php } 	
			 if($wl_theme_options['slide_desc_2']!='') {  ?>
			 <p class="weblizar_slide_desc_2"><?php echo esc_html($wl_theme_options['slide_desc_2']); ?></p>
			 <?php }
				if($wl_theme_options['slide_btn_text_2']!='') { ?>
              <p class="weblizar_slide_btn_text_2"><a class="btn btn-lg btn-primary" href="<?php if($wl_theme_options['slide_btn_link_2']!='') { echo esc_url($wl_theme_options['slide_btn_link_2']); } ?>" role="button"><?php echo esc_html($wl_theme_options['slide_btn_text_2']); ?></a></p>
			  <?php } ?>
            </div>
          </div>
        </div>
		
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!-- /.carousel -->