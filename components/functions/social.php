<?php

/**
 * Global function to pull in social posts from various sources
 */
function load_social()
{
    // Check security
    if (!check_ajax_referer('ajax-nonce', 'security', false)) {
        respond_and_close(false, __('Security Incorrect', 'tmp'));
    }

    respond_and_close();
}

add_action('wp_ajax_load_social', 'load_social'); // for logged in user
add_action('wp_ajax_nopriv_load_social', 'load_social'); // if user not logged in

/**
 * Add custom social content to Timber context
 */
function extend_social_context($context){
    $context['settings']['social'] = array();

    if( $id = get_field('twitter_id', 'option') ) {
        $context['settings']['social']['twitter'] = array(
            'link' => 'http://twitter.com/' . $id . '/',
            'icon' => get_theme_svg('logo--twitter.svg')
        );
    }
    if( $id = get_field('facebook_id', 'option') ) {
        $context['settings']['social']['facebook'] = array(
            'link' => 'http://facebook.com/' . $id . '/',
            'icon' => get_theme_svg('logo--facebook.svg')
        );
    }
    if( $id = get_field('google_plus_id', 'option') ) {
        $context['settings']['social']['google'] = array(
            'link' => 'https://plus.google.com/' . $id . '/posts/',
            'icon' => get_theme_svg('logo--google.scg')
        );
    }
    if( $id = get_field('pinterest_id', 'option') ) {
        $context['settings']['social']['pinterest'] = array(
            'link' => 'https://www.pinterest.com/' . $id . '/',
            'icon' => get_theme_svg('logo--pinterest.svg')
        );
    }
    if( $id = get_field('linkedin_id', 'option') ) {
        $context['settings']['social']['linkedin'] = array(
            'link' => 'https://www.linkedin.com/company/' . $id . '/',
            'icon' => get_theme_svg('logo--linkedin.svg')
        );
    }
    if( $id = get_field('instagram_id', 'option') ) {
        $context['settings']['social']['instagram'] = array(
            'link' => 'https://instagram.com/' . $id . '/',
            'icon' => get_theme_svg('logo--instagram.svg')
        );
    }
    if( $id = get_field('youtube_id', 'option') ) {
        $context['settings']['social']['youtube'] = array(
            'link' => 'https://www.youtube.com/user/' . $id . '/',
            'icon' => get_theme_svg('logo--youtube.svg')
        );
    }
    if( $id = get_field('tumblr_id', 'option') ) {
        $context['settings']['social']['tumblr'] = array(
            'link' => 'http://' . $id . '.tumblr.com/',
            'icon' => get_theme_svg('logo--tumblr.svg')
        );
    }
    $context['settings']['social']['rss'] = array(
        'link' => '/feed/',
        'icon' => get_theme_svg('logo--rss.svg')
    );

    return $context;
}
add_filter('timber/context', 'extend_social_context');

/**
 * Convert plain text tweet content to HTML containing hyperlinks
 *
 * @param $tweet
 * @return mixed
 */
function linkify_tweet($tweet) {
    $tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
    $tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
    $tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);

    return $tweet;
}
