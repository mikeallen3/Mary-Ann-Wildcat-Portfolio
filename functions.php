<?php
/**
 * Mary ann Wildcat Portfolio Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mary ann Wildcat Portfolio
 * @since 1.0.0
 */

/**
 * Enqueue styles
 */
function child_enqueue_styles() {
	wp_enqueue_style( 'mary-ann-wildcat-portfolio-theme-css', get_stylesheet_directory_uri() . '/dist/main.css', 
		array('astra-theme-css'), filemtime(get_stylesheet_directory() . '/dist/main.css'));
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

//Portfolio Tiles
function portfolio_tiles() {
	ob_start();
	
	$args = array(
	    'post_type' => 'post',
	    'category_name' => 'projects',
	    'posts_per_page' => '-1',
	    'orderby'   => 'menu_order',
        'order'     => 'DESC',
	);

	// The Query
	$query = new WP_Query( $args );
	 
	//Set container row
	echo '<div class="portfolio-query">';

	// The Loop
	while ( $query->have_posts() ) {
	    $query->the_post();

	    $thumb_id = get_post_thumbnail_id();
	    $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
	    $thumb_url = $thumb_url_array[0];
	    $thumb_image = get_the_post_thumbnail();

	    if ( has_post_thumbnail() ) {
	    	echo '<a class="portfolio-anchor-link-wrap" href="' . get_the_permalink() . '">';
	    	echo '<span class="overlay-hover-color"></span>';
	    	echo $thumb_image;
	    	echo '<span class="portfolio-title">' . get_the_title() . '</span>';
	    	echo '</a>';
	    }

	}

	//Close container row
	echo '</div>';
	 
	/* Restore original Post Data 
	 * NB: Because we are using new WP_Query we aren't stomping on the 
	 * original $wp_query and it does not need to be reset with 
	 * wp_reset_query(). We just need to set the post data back up with
	 * wp_reset_postdata().
	 */
	wp_reset_postdata();

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'portfolio', 'portfolio_tiles' );


/**
 * Add page attributes to post
 */
function mytheme_add_post_attributes()
{
    add_post_type_support('post', 'page-attributes');
}
add_action('init', 'mytheme_add_post_attributes', 500);

/**
 * Add the menu_order property to the post object being saved
 *
 * @param \WP_Post|\stdClass $post
 * @param WP_REST_Request $request
 *
 * @return \WP_Post
 */
function mytheme_pre_insert_post($post, \WP_REST_Request $request)
{
    $body = $request->get_body();
    if ($body) {
        $body = json_decode($body);
        if (isset($body->menu_order)) {
            $post->menu_order = $body->menu_order;
        }
    }

    return $post;
}
add_filter('rest_pre_insert_post', 'mytheme_pre_insert_post', 12, 2);


/**
 * Load the menu_order property for frontend display in the admin
 *
 * @param \WP_REST_Response $response
 * @param \WP_Post $post
 * @param \WP_REST_Request $request
 *
 * @return \WP_REST_Response
 */
function mytheme_prepare_post(\WP_REST_Response $response, $post, $request)
{
    $response->data['menu_order'] = $post->menu_order;

    return $response;
}
add_filter('rest_prepare_post', 'mytheme_prepare_post', 12, 3);


//Add portfolio image full height
function portfolio_full_height_image(){
	ob_start();
	echo '<div class="fixed-full-height-image"></div>';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'portfolio_image', 'portfolio_full_height_image' );


//Change Next Post Text 
/**
 * Function to change the Next Post/ Previous post text.
 *
 * @param array $args Arguments for next post / previous post links.
 * @return array
 */
function astra_change_next_prev_text( $args ) {
    $next_post = get_next_post();
    $prev_post = get_previous_post();
    $next_text = false;
    if ( $next_post ) {
        $next_text = sprintf(
            '%s <span class="ast-right-arrow">→</span>',
            'Next Project'
        );
    }
    $prev_text = false;
    if ( $prev_post ) {
        $prev_text = sprintf(
            '<span class="ast-left-arrow">←</span> %s',
            'Previous Project'
        );
    }
    $args['next_text'] = $next_text;
    $args['prev_text'] = $prev_text;
    return $args;
}
add_filter( 'astra_single_post_navigation', 'astra_change_next_prev_text' );