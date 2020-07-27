<?php get_header() ?>
<?php get_template_part( 'template-parts/breadcrumbs' ) ?>

<section class="post_blog_bg primary-bg">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			
        	<div class="col-md-8">
				<?php if (have_posts()): ?>
					<?php while (have_posts()): the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'single' ) ?>		
					<?php 
					if ( comments_open() || get_comments_number() ) :
					    comments_template();
					endif;
					?>				
					<?php endwhile ?>
				<?php endif ?>						
				<?php test_wp_one_pagination() ?>

			</div>
				<?php get_sidebar() ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer() ?>