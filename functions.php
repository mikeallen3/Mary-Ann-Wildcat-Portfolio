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
	$args = array(
	    'post_type' => 'post',
	    'category_name' => 'projects',
	    'posts_per_page' => '-1',
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

	    echo '<a class="portfolio-anchor-link-wrap" href="' . get_the_permalink() . '">';
	    echo '<div class="portfolio-container" style="">';
	    echo get_the_post_thumbnail();
	    echo '<span class="portfolio-title">' . get_the_title() . '</span>';
	    echo '</div>';
	    echo '</a>';
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
}
add_shortcode( 'portfolio', 'portfolio_tiles' );