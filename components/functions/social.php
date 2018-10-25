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

    $context['settings']['social']['share']['url'] = preg_replace('/\?.*/', '', current_page_url());
    if (is_home()) {
        $context['settings']['social']['share']['title'] = get_bloginfo('name');
    } elseif (is_404()) {
        $context['settings']['social']['share']['title'] = __('Page not found', 'tmp');
    } elseif (is_archive()) {
        $context['settings']['social']['share']['title'] = single_cat_title('', false);
    } elseif (is_single()) {
        $context['settings']['social']['share']['title'] = single_post_title('', false);
    } else {
        $context['settings']['social']['share']['title'] = get_the_title();
    }

    $context['settings']['social']['twitter'] = array(
        'icon' => get_theme_svg('logo--twitter.svg'),
        'text' => __('Twitter', 'tmp')
    );
    if( $id = get_field('twitter_id', 'option') ) {
        $context['settings']['social']['twitter']['link'] = 'https://twitter.com/' . $id . '/';
    }

    $context['settings']['social']['facebook'] = array(
        'icon' => get_theme_svg('logo--facebook.svg'),
        'text' => __('Facebook', 'tmp')
    );
    if( $id = get_field('facebook_id', 'option') ) {
        $context['settings']['social']['facebook']['link'] = 'https://facebook.com/' . $id . '/';
    }

    $context['settings']['social']['google'] = array(
        'icon' => get_theme_svg('logo--google-plus.svg'),
        'text' => __('Google+', 'tmp')
    );
    if( $id = get_field('google_plus_id', 'option') ) {
        $context['settings']['social']['google']['link'] = 'https://plus.google.com/' . $id . '/posts/';
    }

    $context['settings']['social']['pinterest'] = array(
        'icon' => get_theme_svg('logo--pinterest.svg'),
        'text' => __('Pinterest', 'tmp')
    );
    if( $id = get_field('pinterest_id', 'option') ) {
        $context['settings']['social']['pinterest']['link'] = 'https://www.pinterest.com/' . $id . '/';
    }

    $context['settings']['social']['linkedin'] = array(
        'icon' => get_theme_svg('logo--linkedin.svg'),
        'text' => __('LinkedIn', 'tmp')
    );
    if( $id = get_field('linkedin_id', 'option') ) {
        $context['settings']['social']['linkedin']['link'] = 'https://www.linkedin.com/company/' . $id . '/';
    }

    $context['settings']['social']['instagram'] = array(
        'icon' => get_theme_svg('logo--instagram.svg'),
        'text' => __('Instagram', 'tmp')
    );
    if( $id = get_field('instagram_id', 'option') ) {
        $context['settings']['social']['instagram']['link'] = 'https://instagram.com/' . $id . '/';
    }

    $context['settings']['social']['youtube'] = array(
        'icon' => get_theme_svg('logo--youtube.svg'),
        'text' => __('Youtube', 'tmp')
    );
    if( $id = get_field('youtube_id', 'option') ) {
        $context['settings']['social']['youtube']['link'] = 'https://www.youtube.com/user/' . $id . '/';
    }

    $context['settings']['social']['tumblr'] = array(
        'icon' => get_theme_svg('logo--tumblr.svg'),
        'text' => __('Tumblr', 'tmp')
    );
    if( $id = get_field('tumblr_id', 'option') ) {
        $context['settings']['social']['tumblr']['link'] = 'https://' . $id . '.tumblr.com/';
    }

    $context['settings']['social']['rss'] = array(
        'link' => '/feed/',
        'icon' => get_theme_svg('logo--rss.svg'),
        'text' => __('RSS', 'tmp')
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
