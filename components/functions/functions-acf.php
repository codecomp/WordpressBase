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

//Add extra options pages to ACF
if(function_exists('acf_add_options_page')) {

    acf_add_options_page();
    acf_add_options_sub_page('Social');
    acf_add_options_sub_page('Site Options');
    acf_add_options_sub_page('Emails');

}

//Add social Field groups
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_social',
		'title' => 'Social Setup',
		'fields' => array (
			array (
				'key' => 'field_53e359d6636c1',
				'label' => 'Social Media',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_53e359e0636c2',
				'label' => 'Facebook ID',
				'name' => 'facebook_id',
				'type' => 'text',
				'instructions' => 'Your Facebook id as shown after facebook.com/',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35a6e636c5',
				'label' => 'Twitter ID',
				'name' => 'twitter_id',
				'type' => 'text',
				'instructions' => 'Your twitter username without the @',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35a32636c4',
				'label' => 'Google+ ID',
				'name' => 'googleplus_id',
				'type' => 'text',
				'instructions' => 'Your 21 digit Google+ id as shown after plus.google.com/',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35a7e636c6',
				'label' => 'Linkedin ID',
				'name' => 'linkedin_id',
				'type' => 'text',
				'instructions' => 'Your linkedIn company ID ',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35aea636c7',
				'label' => 'Pinterest ID',
				'name' => 'pinterest_id',
				'type' => 'text',
				'instructions' => 'Your Pinterest username',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35b1b636c8',
				'label' => 'Instagram ID',
				'name' => 'instagram_id',
				'type' => 'text',
				'instructions' => 'Your Instagram username',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35b25636c9',
				'label' => 'YouTube ID',
				'name' => 'youtube_id',
				'type' => 'text',
				'instructions' => 'Your Youtube username',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35b35636ca',
				'label' => 'Tumblr ID',
				'name' => 'tumblr_id',
				'type' => 'text',
				'instructions' => 'Your Tumblr username',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35c1d03fb2',
				'label' => 'Twitter API',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_53e35c3803fb3',
				'label' => 'Twitter Consumer Key',
				'name' => 'twitter_consumer_key',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35c3b03fb4',
				'label' => 'Twitter Consumer Secret',
				'name' => 'twitter_consumer_secret',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35c4403fb5',
				'label' => 'Twitter Access Token',
				'name' => 'twitter_access_token',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53e35c4c03fb6',
				'label' => 'Twitter Access Secret',
				'name' => 'twitter_access_secret',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-social',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
