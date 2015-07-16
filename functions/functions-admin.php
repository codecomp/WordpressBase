<?php

/**
 * Remove dashboard widgets
 */
function remove_dashboard_widgets() {
	global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);

}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

/**
 * Remove Welcome panel from dashboard
 */
remove_action( 'welcome_panel', 'wp_welcome_panel' );

/**
 * Remove unused admin sections, uncomment to remove this for relevant users
 */
function remove_menus(){

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
		'admin'
	);
	$current_user = wp_get_current_user();

	if (!in_array($current_user->user_login, $admins)) {
		//Hide update notices
		remove_action( 'admin_notices', 'update_nag', 3 );

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
add_action('admin_menu', 'remove_menus', 999);

/**
 * Remove Update notifications
 */
function remove_core_updates()
{
	if( !current_user_can('update_core') ){
		return;
	}
	add_action('init', create_function('$a',"remove_action( 'init', 'wp_version_check' );"),2);
	add_filter('pre_option_update_core','__return_null');
	add_filter('pre_site_transient_update_core','__return_null');
}
add_action('after_setup_theme','remove_core_updates');

/**
 * Remove plugin update notifications
 */
remove_action('load-update-core.php','wp_update_plugins');
add_filter('pre_site_transient_update_plugins','__return_null');

/**
 * Only allow certain roles into admin section
 */
function restrict_admin(){
	if ( is_admin() && (!is_role('super_admin') && !is_role('administrator') && !is_role('editor') && !is_role('author') && !is_role('contributor')) && !(defined('DOING_AJAX') && DOING_AJAX) ) {
		wp_redirect(home_url());
		exit;
	}
}
add_action('init', 'restrict_admin', 0);


/**
 * Customises the wordpress admin footer
 */
function modify_footer_admin () {
    echo 'Created by <a target="_blank" href="http://ahoy.co.uk" title="Visit Ahoy">Ahoy</a>. Powered by <a target="_blank" href="http://www.wordpress.org" title="Visit WordPress">WordPress</a>';
}
add_filter('admin_footer_text', 'modify_footer_admin');