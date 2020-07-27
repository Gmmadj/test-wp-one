<aside class="col-md-4">
	<div class="side_blog_bg">
	<?php // Dynamic Sidebar
	if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
	
		

		<?php dynamic_sidebar( 'sidebar-1' ) ?>

		<div class="news_sletter">
			<div class="side_bar_sub_heading">
				<h6> Newsletter </h6>							
			</div>
			
			<p> Subscribe to our email newsletter for useful tips and resources.</p>
			
			<form>
		  		<div class="form-group blog_form">
					<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email Address" >
			 	 </div>
				  
			 	 <div class="search_btn-3">
					<button class="btn btn-default" type="submit">  Subscribe </button>	
				 </div>
		   </form>
	   
		</div>
		
	<?php endif; // End Dynamic Sidebar sidebar-1 ?>
	</div>	
</aside>