<?php 
/**
 * Template for displaying search forms in Theme
 *
 * @package WordPress
 * @subpackage weblizar
 */
?>
<div class="sidebar-content tags blog-search">
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">		
		<input type="text" class="blog-search-input text-input"  name="s" id="s" placeholder="<?php esc_attr_e( "Search..", 'weblizar' ); ?>" />
		<button class="blog-search-button icon-search "><i class="fa fa-search"></i></button>
	</form>
</div>