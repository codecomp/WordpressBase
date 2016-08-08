<?php

/**
 * Update WordPress settings, declare heme support and remove
 * global hook events for security and theme consistency
 */
function theme_reset()
{
	// Remove unwanted things
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles' );

	//Customise post type support
	add_post_type_support('post', 'excerpt');
	remove_post_type_support('post', 'custom-fields');
	remove_post_type_support('post', 'page-attributes');
}
add_action('init', 'theme_reset');