<?php

/**
 * Include theme required css and javascript files and localise
 * php variables for JavaScript use
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('site-styles', get_template_directory_uri() . '/dist/css/main.css', array(),
        filemtime(get_template_directory() . '/dist/css/main.css'));
    wp_enqueue_script('site-scripts', get_template_directory_uri() . '/dist/js/main.js', array(),
        filemtime(get_template_directory() . '/dist/js/main.js'), true);

    $localisation = array(
        'template' => get_template_directory_uri(),
        'assets' => get_template_directory_uri() . '/dist/',
        'site' => get_site_url(),
        'ajax' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce'),
        'gmap_key' => get_field('google_maps_api_key', 'options'),
        'translate' => array(
            'error' => __('There appears to have been a problem please try again later', 'tmp'),
            'thanks' => __('Thank you', 'tmp')
        )
    );

    wp_localize_script('site-scripts', 'WP', $localisation);
});

// Remove CSS variables --wp--preset--color/gradient/duotone
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );

// Remove SVG definitions for gradient/duotone
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

/**
 * Register theme support
 */
add_action('after_setup_theme', function () {
    // Enable plugins to manage the document title
    add_theme_support('title-tag');

    // Enable post thumbnails
    add_theme_support('post-thumbnails');

    // Enable post formats
    add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

    // Enable HTML5 markup support
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    // Load textdomain for translations
    load_theme_textdomain('tmp', TEMPLATEPATH . '/components/languages');

    //Register navigation menus
    register_nav_menus(
        array(
            'main-nav' => 'Main Navigation Menu',
            'footer-nav' => 'Footer Navigation Menu'
        )
    );

    //Add image sizes (name, width, height, crop)
    add_image_size('email-banner', 600, null, true); // used for contact emails

    // Register admin editor stylesheet
    add_editor_style(get_template_directory_uri() . '/dist/css/admin-editor-styles.css');
});
