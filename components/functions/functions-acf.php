<?php
/**
 * Include any Options page setup or field set installations here
 */

/**
 * Add Join to search query
 * Part 1 of 3 part functions to add ACF fields to search page results
 *
 * @param $join
 * @return mixed
 */
function acf_search_join( $join ) {
    global $wpdb;

    if ( is_search() && is_main_query() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'acf_search_join' );

/**
 * Add meta value to where query
 * Part 2 of 3 part functions to add ACF fields to search page results
 *
 * @param $where
 * @return mixed
 */
function acf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() && is_main_query() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );

        //Make sure we don't find results for ACF generated meta_value -> ACF field entries
        $where = " AND (".$wpdb->postmeta.".meta_value NOT REGEXP 'field_[a-z0-9]{13}')" . $where;
    }

    return $where;
}
add_filter( 'posts_where', 'acf_search_where' );

/**
 * Force distinct on where query
 * Part 3 of 3 part functions to add ACF fields to search page results
 *
 * @param $where
 * @return string
 */
function acf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() && is_main_query() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'acf_search_distinct' );

/**
 * Created ACF options page based on content passed into
 * 'theme_options_tab' hook
 */
function init_theme_acf_options(){
	// Only run if we have the available ACF functions
	if( !function_exists('acf_add_options_page') || !function_exists('acf_add_local_field_group') )
		return;

	// Setup arrays to create options page
	$options_tabs = array();
	$tmp_tabs 	  = apply_filters('theme_options_tabs', array());

	foreach( $tmp_tabs as $name => $fields ){
		//Setup tab
		$tab = array(
			'key' => 'site_options_tab_' . strtolower(str_replace(' ', '_', $name)),
			'label' => $name,
			'type' => 'tab',
			'placement' => 'left',
			'endpoint' => 0,
		);
		array_push($options_tabs, $tab);

		//Add fields to tabs
		foreach ($fields as $f) {
			$field_key = ($f['key'] ? $f['key'] : strtolower(str_replace(' ', '_', $f['name'])));

			$field = array (
				'key' => 'social_options_' . $field_key,
				'label' => $f['name'],
				'name' => $field_key
			);

			foreach ($f as $k => $v) {
				if ($k !== 'name') {
					$field[$k] = $v;
				}
			}

			array_push($options_tabs, $field);
		}
	}

	// Create settings page
	acf_add_options_page('Site Settings');

	// Create field group based on tabs created previously
	acf_add_local_field_group(array(
		'key' => 'site_options',
		'title' => 'Site Options',
		'fields' => $options_tabs,
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-site-settings',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));
}
add_action('init', 'init_theme_acf_options', 9999);
