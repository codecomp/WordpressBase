<?php

/**
 * Update WordPress settings, declare heme support and remove
 * global hook events for security and theme consistency
 */
add_action('init', function(){
	// Remove unwanted things
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles' );

	//Customise post type support
	remove_post_type_support('post', 'custom-fields');
	remove_post_type_support('post', 'page-attributes');
});

/**
 * Disable X-Pingback HTTP Header.
 */
add_filter('wp_headers', function($headers, $wp_query){
    if(isset($headers['X-Pingback'])){
        // Drop X-Pingback
        unset($headers['X-Pingback']);
    }
    return $headers;
}, 11, 2);

/**
 * Disable XMLRPC by hijacking and blocking the option.
 */
add_filter('pre_option_enable_xmlrpc', function($state){
    return '0'; // return $state; // To leave XMLRPC intact and drop just Pingback
});

/**
 * Hijack pingback_url for get_bloginfo (<link rel="pingback" />).
 */
add_filter('bloginfo_url', function($output, $property){
    return ($property == 'pingback_url') ? null : $output;
}, 11, 2);


/**
 * Disable pingback.ping functionality while leaving XMLRPC intact
 */
add_action('xmlrpc_call', function($method){
    if($method != 'pingback.ping') return;
    wp_die(
        'Pingback functionality is disabled on this Blog.',
        'Pingback Disabled!',
        array('response' => 403)
    );
});