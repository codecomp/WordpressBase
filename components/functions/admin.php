<?php

/**
 * Remove hooked functionality
 */
function functions_admin_setup()
{
    //Remove word press version number from theme
    remove_action('wp_head', 'wp_generator');

    //Return no error upon failed login attempt
    add_filter('login_errors', create_function('$a', 'return null;'));

    //Remove Welcome panel from dashboard
    remove_action('welcome_panel', 'wp_welcome_panel');

    // Remove plugin update notifications
    remove_action('load-update-core.php', 'wp_update_plugins');
    add_filter('pre_site_transient_update_plugins', '__return_null');
}

add_action('after_setup_theme', 'functions_admin_setup');


/**
 * Remove dashboard widgets
 */
function remove_dashboard_widgets()
{
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
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

/**
 * Remove unused admin sections, uncomment to remove this for relevant users
 */
function remove_admin_menu()
{

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

}

add_action('admin_menu', 'remove_admin_menu', 999);

/**
 * Modify Wordpress admin bar menu
 */
function remove_admin_bar_menu()
{
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
}

add_action('admin_bar_menu', 'remove_admin_bar_menu', 999);

/**
 * Remove the Screen options tab from admin pages
 *
 * @param $old_help
 * @param $screen_id
 * @param $screen
 * @return mixed
 */
function remove_help_tabs($old_help, $screen_id, $screen)
{
    $screen->remove_help_tabs();
    return $old_help;
}

add_filter('screen_options_show_screen', '__return_false');
add_filter('contextual_help', 'remove_help_tabs', 999, 3);

/**
 * Remove Update notifications
 */
function remove_core_updates()
{
    if (!current_user_can('update_core')) {
        return;
    }
    add_action('init', create_function('$a', "remove_action( 'init', 'wp_version_check' );"), 2);
    add_filter('pre_option_update_core', '__return_null');
    add_filter('pre_site_transient_update_core', '__return_null');
}

add_action('after_setup_theme', 'remove_core_updates');

/**
 * Customises the wordpress admin footer
 */
function modify_footer_admin()
{
    echo 'Created by <a target="_blank" href="https://ahoy.co.uk" title="Visit Ahoy">Ahoy</a>. Powered by <a target="_blank" href="http://www.wordpress.org" title="Visit WordPress">WordPress</a>';
}

add_filter('admin_footer_text', 'modify_footer_admin');

/**
 * Add buttons to TinyMCE toolbar
 *
 * @param $buttons
 * @return array
 */
function add_tinymce_buttons($buttons)
{
    $buttons[] = 'formatselect';

    return $buttons;
}

add_filter('mce_buttons', 'add_tinymce_buttons');

/**
 * Remove TinyMCE Buttons from mce_buttons hook
 *
 * @param $buttons
 * @return array
 */
function remove_tinymce_buttons_1($buttons)
{
    $remove = array(
        'wp_adv',
        'strikethrough',
        'hr',
        'wp_more',
        'fullscreen'
    );

    return array_diff($buttons, $remove);
}

add_filter('mce_buttons', 'remove_tinymce_buttons_1');

/**
 * Remove TinyMCE Buttons from mce_buttons_2 hook
 *
 * @param $buttons
 * @return array
 */
function remove_tinymce_buttons_2($buttons)
{
    $remove = array(
        'underline',
        'justifyfull',
        'forecolor',
        '|',
        'pastetext',
        'pasteword',
        'removeformat',
        'charmap',
        'outdent',
        'indent',
        'undo',
        'alignjustify',
        'redo',
        'wp_help',
        'formatselect'
    );

    return array_diff($buttons, $remove);
}

add_filter('mce_buttons_2', 'remove_tinymce_buttons_2');

/**
 * Add admin theme CSS to admin UI
 */
function theme_editor_styles()
{
    add_editor_style(get_template_directory_uri() . '/dist/css/admin-editor-styles.css');
}

add_action('admin_init', 'theme_editor_styles');