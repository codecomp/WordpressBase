<?php

/**
 * Update WordPress settings, declare heme support and remove
 * global hook events for security and theme consistency
 */
add_action('init', function () {
    // Remove unwanted things
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');

    //Customise post type support
    remove_post_type_support('post', 'custom-fields');
    remove_post_type_support('post', 'page-attributes');
});

/**
 * Disable X-Pingback HTTP Header.
 */
add_filter('wp_headers', function ($headers, $wp_query) {
    if (isset($headers['X-Pingback'])) {
        // Drop X-Pingback
        unset($headers['X-Pingback']);
    }
    return $headers;
}, 11, 2);

/**
 * Disable XMLRPC by hijacking and blocking the option.
 */
add_filter('pre_option_enable_xmlrpc', function ($state) {
    return '0'; // return $state; // To leave XMLRPC intact and drop just Pingback
});

/**
 * Hijack pingback_url for get_bloginfo (<link rel="pingback" />).
 */
add_filter('bloginfo_url', function ($output, $property) {
    return ($property == 'pingback_url') ? null : $output;
}, 11, 2);


/**
 * Disable pingback.ping functionality while leaving XMLRPC intact
 */
add_action('xmlrpc_call', function ($method) {
    if ($method != 'pingback.ping') {
        return;
    }
    wp_die(
        'Pingback functionality is disabled on this Blog.',
        'Pingback Disabled!',
        array('response' => 403)
    );
});

/**
 * Remove enqueued scripts and css files
 */
add_action('wp_enqueue_scripts', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
});

/**
 * General admin cleanup
 */
add_action('after_setup_theme', function () {
    //Remove word press version number from theme
    remove_action('wp_head', 'wp_generator');

    //Return no error upon failed login attempt
    add_filter('login_errors', create_function('$a', 'return null;'));

    //Remove Welcome panel from dashboard
    remove_action('welcome_panel', 'wp_welcome_panel');

    // Remove plugin update notifications
    remove_action('load-update-core.php', 'wp_update_plugins');
    add_filter('pre_site_transient_update_plugins', '__return_null');

    // Remove update notification
    if (!current_user_can('update_core')) {
        return;
    }
    add_action('init', create_function('$a', "remove_action( 'init', 'wp_version_check' );"), 2);
    add_filter('pre_option_update_core', '__return_null');
    add_filter('pre_site_transient_update_core', '__return_null');
});


/**
 * Remove dashboard widgets
 */
add_action('wp_dashboard_setup', function () {
    global $wp_meta_boxes;

    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
});

/**
 * Remove unused admin sections, uncomment to remove this for relevant users
 */
add_action('admin_menu', function () {

    //Remove these menu pages for all users
    //remove_menu_page( 'index.php' );					//Dashboard
    //remove_menu_page( 'edit.php' );					//Posts
    //remove_menu_page( 'upload.php' );					//Media
    //remove_menu_page( 'edit.php?post_type=page' );	//Pages
    //remove_menu_page( 'edit-comments.php' );			//Comments
    //remove_menu_page( 'themes.php' );					//Appearance
    //remove_menu_page( 'plugins.php' );				//Plugins
    //remove_menu_page( 'users.php' );					//Users
    //remove_menu_page( 'tools.php' );					//Tools
    //remove_menu_page( 'options-general.php' );		//Settings
    //remove_menu_page( 'edit.php?post_type=acf' );		//ACF
    //remove_menu_page( 'cptui_main_menu' );			//CPT UI

    //Add all super admins to this array
    $admins = array(
        'ahoycreative'
    );
    $current_user = wp_get_current_user();

    if (!in_array($current_user->user_login, $admins)) {
        //Hide update notices
        remove_action('admin_notices', 'update_nag', 3);

        //Hide these pages from all users not listed as super admins
        //remove_menu_page( 'index.php' );											//Dashboard
        //remove_menu_page( 'update-core.php' );									//Update
        //remove_submenu_page( 'index.php', 'update-core.php' );					//Update //TODO see which one is which
        //remove_menu_page( 'edit.php' );											//Posts
        //remove_menu_page( 'upload.php' );											//Media
        //remove_menu_page( 'edit.php?post_type=page' );							//Pages
        //remove_menu_page( 'edit-comments.php' );									//Comments
        //remove_menu_page( 'themes.php' );											//Appearance
        //remove_submenu_page( 'themes.php', 'widgets.php' );						//Appearance -> Widgets
        //remove_submenu_page( 'themes.php', 'customize.php' );						//Appearance -> Customise
        //remove_submenu_page( 'themes.php', 'theme-editor.php' );					//Appearance -> ThemeEditor
        //remove_menu_page( 'plugins.php' );										//Plugins
        //remove_menu_page( 'users.php' );											//Users
        //remove_menu_page( 'tools.php' );											//Tools
        //remove_submenu_page( 'tools.php', 'import.php' );							//Tools -> Import
        //remove_submenu_page( 'tools.php', 'export.php' );							//Tools -> Export
        //remove_menu_page( 'options-general.php' );								//Settings
        //remove_submenu_page( 'options-general.php', 'options-writing.php' );		//Settings -> Writing
        //remove_submenu_page( 'options-general.php', 'options-reading.php' );		//Settings -> Reading
        //remove_submenu_page( 'options-general.php', 'options-discussion.php' );	//Settings -> Discussion
        //remove_submenu_page( 'options-general.php', 'options-media.php' );		//Settings -> Media
        //remove_submenu_page( 'options-general.php', 'options-permalink.php' );	//Settings -> Permalink
        //remove_menu_page( 'edit.php?post_type=acf' );								//ACF
        //remove_menu_page( 'cptui_main_menu' );									//CPT UI
    }
}, 999);

/**
 * Modify Wordpress admin bar menu
 */
add_action('admin_bar_menu', function () {
    global $wp_admin_bar;

    //$wp_admin_bar->remove_node( 'new-content' );
    //$wp_admin_bar->remove_node( 'edit' );
    $wp_admin_bar->remove_node('comments');
    //$wp_admin_bar->remove_node( 'new-post' );
    //$wp_admin_bar->remove_node( 'new-page' );
    $wp_admin_bar->remove_node('new-media');
    $wp_admin_bar->remove_node('new-user');
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('search');
    $wp_admin_bar->remove_node('customize');

    // $new_content_node = $wp_admin_bar->get_node('new-content');
    // $new_content_node->href = admin_url( 'post-new.php?post_type=page');
    // $wp_admin_bar->add_node($new_content_node);
}, 999);

/**
 * Remove the Screen options tab from admin pages
 *
 * @param $old_help
 * @param $screen_id
 * @param $screen
 * @return mixed
 */
add_filter('screen_options_show_screen', '__return_false');
add_filter('contextual_help', function ($old_help, $screen_id, $screen) {
    $screen->remove_help_tabs();
    return $old_help;
}, 999, 3);
