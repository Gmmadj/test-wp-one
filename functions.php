<?php 

require_once get_template_directory() .'/inc/class-test-wp-one-recent-post-widget.php';

function test_wp_one_register_widget(  )
{
	register_widget( 'test_wp_one_Widget_Recent_Posts' );
}

add_action( 'widgets_init', 'test_wp_one_register_widget' );

function test_wp_one_setup()
{
	load_theme_textdomain('test_wp_one');

	add_theme_support('title-tag');
	add_theme_support('custom-logo', [
		'height' => 31,
		'width'  => 134,
		'flex-height' => true,
	]);
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(720, 446);

	add_theme_support( 'html5', [
		'search_form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	] );
	add_theme_support( 'post-formats', [
		'aside',
		'image',
		'video',
		'gallery',
	] );

	register_nav_menu( 'primary', 'Главное меню' );
}

add_action('after_setup_theme', 'test_wp_one_setup');

add_action( 'wp_enqueue_scripts', 'test_wp_one_scripts' );

function test_wp_one_scripts() 
{

	// Style
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() .'/css/bootstrap.min.css' );
	wp_enqueue_style( 'animate', get_template_directory_uri() .'/css/animate.min.css' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/css/font-awesome.min.css' );
	wp_enqueue_style( 'style-css', get_stylesheet_uri() );

	// Scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js');
	wp_enqueue_script('css3-animate-it', get_template_directory_uri() . '/js/css3-animate-it.js');
	wp_enqueue_script('jquery-easing', get_template_directory_uri() . '/js/jquery.easing.min.js');
	wp_enqueue_script('agency', get_template_directory_uri() . '/js/agency.js');
}

add_filter('excerpt_more', function($more) {
	return '...';
});

/**
 * Breadcrumb - Хлебные крошки
 */
function test_wp_one_the_breadcrumb() {
	global $post;
	if(!is_home()){ 
	   echo '<li><a href="'.site_url().'"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li> <li> / </li> ';
		if(is_single()){ // posts
		the_category(', ');
		echo " <li> / </li> ";
		echo '<li>';
			the_title();
		echo '</li>';
		}
		elseif (is_page()) { // pages
			if ($post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach ($breadcrumbs as $crumb) echo $crumb . '<li> / </li> ';
			}
			echo the_title();
		}
		elseif (is_category()) { // category
			global $wp_query;
			$obj_cat = $wp_query->get_queried_object();
			$current_cat = $obj_cat->term_id;
			$current_cat = get_category($current_cat);
			$parent_cat = get_category($current_cat->parent);
			if ($current_cat->parent != 0) 
				echo(get_category_parents($parent_cat, TRUE, ' <li> / </li> '));
			single_cat_title();
		}
		elseif (is_search()) { // search pages
			echo 'Search results "' . get_search_query() . '"';
		}
		elseif (is_tag()) { // tags
			echo single_tag_title('', false);
		}
		elseif (is_day()) { // archive (days)
			echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> <li> / </li> ';
			echo '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></li> <li> / </li> ';
			echo get_the_time('d');
		}
		elseif (is_month()) { // archive (months)
			echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> <li> / </li>';
			echo get_the_time('F');
		}
		elseif (is_year()) { // archive (years)
			echo get_the_time('Y');
		}
		elseif (is_author()) { // authors
			global $author;
			$userdata = get_userdata($author);
			echo '<li>Posted ' . $userdata->display_name . '</li>';
		} elseif (is_404()) { // if page not found
			echo '<li>Error 404</li>';
		}
	 
		if (get_query_var('paged')) // number of page
			echo ' (' . get_query_var('paged').'- page)';
	 
	} else { // home
	   $pageNum=(get_query_var('paged')) ? get_query_var('paged') : 1;
	   if($pageNum>1)
	      echo '<li><a href="'.site_url().'"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li> <li> / </li> '.$pageNum.'- page</li>';
	   else
	      echo '<li><i class="fa fa-home" aria-hidden="true"></i>Home</li>';
	}
}

/**
 * Pagination - Нумерация страниц
 */
function test_wp_one_pagination( $args = array() ) {
    
    $defaults = array(
        'range'           => 4,
        'custom_query'    => FALSE,
        'previous_string' => __( 'Previous', 'text-domain' ),
        'next_string'     => __( 'Next', 'text-domain' ),
        'before_output'   => '<div class="next_page"><ul class="page-numbers">',
        'after_output'    => '</ul></div>'
    );
    
    $args = wp_parse_args( 
        $args, 
        apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
    );
    
    $args['range'] = (int) $args['range'] - 1;
    if ( !$args['custom_query'] )
        $args['custom_query'] = @$GLOBALS['wp_query'];
    $count = (int) $args['custom_query']->max_num_pages;
    $page  = intval( get_query_var( 'paged' ) );
    $ceil  = ceil( $args['range'] / 2 );
    
    if ( $count <= 1 )
        return FALSE;
    
    if ( !$page )
        $page = 1;
    
    if ( $count > $args['range'] ) {
        if ( $page <= $args['range'] ) {
            $min = 1;
            $max = $args['range'] + 1;
        } elseif ( $page >= ($count - $ceil) ) {
            $min = $count - $args['range'];
            $max = $count;
        } elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }
    
    $echo = '';
    $previous = intval($page) - 1;
    $previous = esc_attr( get_pagenum_link($previous) );
    
/*
    $firstpage = esc_attr( get_pagenum_link(1) );
    if ( $firstpage && (1 != $page) )
        $echo .= '<li class="previous"><a href="' . $firstpage . '" class="page-numbers">' . __( 'First', 'text-domain' ) . '</a></li>';
*/

    if ( $previous && (1 != $page) )
        $echo .= '<li><a href="' . $previous . '" class="page-numbers" title="' . __( 'previous', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';
    
    if ( !empty($min) && !empty($max) ) {
        for( $i = $min; $i <= $max; $i++ ) {
            if ($page == $i) {
                $echo .= '<li class="active"><span class="page-numbers current">' . str_pad( (int)$i, 1, '0', STR_PAD_LEFT ) . '</span></li>';
            } else {
                $echo .= sprintf( '<li><a href="%s" class="page-numbers">%2d</a></li>', esc_attr( get_pagenum_link($i) ), $i );
            }
        }
    }
    
    $next = intval($page) + 1;
    $next = esc_attr( get_pagenum_link($next) );
    if ($next && ($count != $page) )
        $echo .= '<li><a href="' . $next . '" class="page-numbers" title="' . __( 'next', 'text-domain') . '">' . $args['next_string'] . '</a></li>';
    
/*
    $lastpage = esc_attr( get_pagenum_link($count) );
    if ( $lastpage ) {
        $echo .= '<li class="next"><a href="' . $lastpage . '" class="page-numbers" >' . __( 'Last', 'text-domain' ) . '</a></li>';
    }
*/

    if ( isset($echo) )
        echo $args['before_output'] . $echo . $args['after_output'];
}

/**
 * New Setting Customize
 */
function test_wp_one_customize_register( $wp_customize )
{
	/**
	 * Social Section
	 */
	$wp_customize->add_section('social_section', [
		'title'    => __('Social setting', 'test_wp_one') ,
		'priority' => 30,
	]);

	$wp_customize->add_setting('header_social', [
		'default'   => __('Наши фавариты в мобильных приложениях', 'test_wp_one'),
		'transport' => 'refresh',	
	]);

	$wp_customize->add_setting('facebook_social', [
		'default'   => __('URL facebook', 'test_wp_one'),
		'transport' => 'refresh',	
	]);

	$wp_customize->add_setting('twitter_social', [
		'default'   => __('URL twitter', 'test_wp_one'),
		'transport' => 'refresh',	
	]);

	$wp_customize->add_control('header_social', [
		'label' => __('Social header in footer', 'test_wp_one'),
		'section' => 'social_section',
		'settings' => 'header_social',
		'type' => 'text',
	]);

	$wp_customize->add_control('facebook_social', [
		'label' => __('URL facebook', 'test_wp_one'),
		'section' => 'social_section',
		'settings' => 'facebook_social',
		'type' => 'text',
	]);

	$wp_customize->add_control('twitter_social', [
		'label' => __('URL twitter', 'test_wp_one'),
		'section' => 'social_section',
		'settings' => 'twitter_social',
		'type' => 'text',
	]);

	/**
	 * Footer Setting Section
	 */
	$wp_customize->add_section('footer_setting_section', [
		'title'    => __('Footer setting', 'test_wp_one') ,
		'priority' => 30,
	]);

	$wp_customize->add_setting('footer_copy_social', [
		'default'   => __('2016 &copy; Copyright Applayers. All rights Reserved. Powered By Free', 'test_wp_one', 'test_wp_one'),
		'transport' => 'refresh',	
	]);

	$wp_customize->add_control('twitter_social', [
		'label' => __('Footer copy'),
		'section' => 'footer_setting_section',
		'settings' => 'footer_copy_social',
		'type' => 'text',
	]);

}
add_action('customize_register', 'test_wp_one_customize_register');

/**
 * Add a sidebar
 */
function test_wp_one_widgets_init()
{
	/**
	 * Creates a sidebar
	 * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
	 */
	$args = array(
		'name'          => __( 'Main Sidebar', 'test_wp_one' ),
		'id'            => 'sidebar-1',
		'description'   => 'One sidebar',
		'before_widget' => '<div id="%1$s" class="sidebar_wrap %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="side_bar_heading">
								<h6>',
		'after_title'   => '</h6>							
							</div>',
	);
	
	register_sidebar( $args );
	
}

add_action( 'widgets_init', 'test_wp_one_widgets_init' );

/**
 * New Walker Category
 */
function test_wp_one_widget_categories($args)
{
	$walker = new Walker_Categories_test_wp_one();
	$args = array_merge($args, ['walker' => $walker]);

	return $args;
}

add_filter('widget_categories_args', 'test_wp_one_widget_categories');

class Walker_Categories_test_wp_one extends Walker_Category
{
	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters( 'list_cats', esc_attr( $category->name ), $category );

		// Don't generate an element if the category name is empty.
		if ( '' === $cat_name ) {
			return;
		}

		$atts         = array();
		$atts['href'] = get_term_link( $category );

		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			/**
			 * Filters the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string $description Category description.
			 * @param object $category    Category object.
			 */
			$atts['title'] = strip_tags( apply_filters( 'category_description', $category->description, $category ) );
		}

		/**
		 * Filters the HTML attributes applied to a category list item's anchor element.
		 *
		 * @since 5.2.0
		 *
		 * @param array   $atts {
		 *     The HTML attributes applied to the list item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $href  The href attribute.
		 *     @type string $title The title attribute.
		 * }
		 * @param WP_Term $category Term data object.
		 * @param int     $depth    Depth of category, used for padding.
		 * @param array   $args     An array of arguments.
		 * @param int     $id       ID of the current category.
		 */
		$atts = apply_filters( 'category_list_link_attributes', $atts, $category, $depth, $args, $id );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$cat_number .= '<span>' . number_format_i18n( $category->count ) . '</span>';

		$link = sprintf(
			'<a%s><i class="fa fa-folder-open-o" aria-hidden="true"></i>%s%s</a>',
			$attributes,
			$cat_name,
			$cat_number,
		);

		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
			$link .= ' ';

			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}

			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

			if ( empty( $args['feed'] ) ) {
				/* translators: %s: Category name. */
				$alt = ' alt="' . sprintf( __( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$alt   = ' alt="' . $args['feed'] . '"';
				$name  = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}

			$link .= '>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= $name;
			} else {
				$link .= "<img src='" . esc_url( $args['feed_image'] ) . "'$alt" . ' />';
			}
			$link .= '</a>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= ')';
			}
		}


		if ( 'list' == $args['style'] ) {
			$output     .= "\t<li";
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);

			if ( ! empty( $args['current_category'] ) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms(
					array(
						'taxonomy'   => $category->taxonomy,
						'include'    => $args['current_category'],
						'hide_empty' => false,
					)
				);

				foreach ( $_current_terms as $_current_term ) {
					if ( $category->term_id == $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
						$link          = str_replace( '<a', '<a aria-current="page"', $link );
					} elseif ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
					while ( $_current_term->parent ) {
						if ( $category->term_id == $_current_term->parent ) {
							$css_classes[] = 'current-cat-ancestor';
							break;
						}
						$_current_term = get_term( $_current_term->parent, $category->taxonomy );
					}
				}
			}

			/**
			 * Filters the list of CSS classes to include with each category in the list.
			 *
			 * @since 4.2.0
			 *
			 * @see wp_list_categories()
			 *
			 * @param array  $css_classes An array of CSS classes to be applied to each list item.
			 * @param object $category    Category data object.
			 * @param int    $depth       Depth of page, used for padding.
			 * @param array  $args        An array of wp_list_categories() arguments.
			 */
			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );
			$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';

			$output .= $css_classes;
			$output .= ">$link\n";
		} elseif ( isset( $args['separator'] ) ) {
			$output .= "\t$link" . $args['separator'] . "\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}
}

/**
 * Tag Cloud Filter
 */
function test_wp_one_tag_cloud( $args )
{
	$args['smallest'] 	= 14;
	$args['largest'] 	= 14;
	$args['unit'] 		= 'px';
	$args['format'] 	= 'list';

	return $args;	
}

add_filter( 'widget_tag_cloud_args', 'test_wp_one_tag_cloud' );