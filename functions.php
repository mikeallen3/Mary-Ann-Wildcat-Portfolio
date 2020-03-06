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