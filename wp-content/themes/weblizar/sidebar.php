<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage weblizar
 */
?>
<div class="col-md-3 col-sm-3">
	<!-- Sidebar Block -->
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-primary') ) { ?> 
	<?php the_widget('WP_Widget_Archives'); ?>
	<?php the_widget('WP_Widget_Categories'); ?>
	<?php } ?>
</div>