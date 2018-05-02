<?php

/**
 * Created ACF options page
 */
function init_theme_acf_options(){
    // Only run if we have the available ACF functions
    if( !function_exists('acf_add_options_page') )
        return;

    // Create settings page
    acf_add_options_page('Site Settings');
}
add_action('init', 'init_theme_acf_options', 9999);

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