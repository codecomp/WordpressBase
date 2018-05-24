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
