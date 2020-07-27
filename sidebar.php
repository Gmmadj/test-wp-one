<aside class="col-md-4">
	<div class="side_blog_bg">
	<?php // Dynamic Sidebar
	if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ) ?>
	<?php endif; // End Dynamic Sidebar sidebar-1 ?>
	</div>	
</aside>