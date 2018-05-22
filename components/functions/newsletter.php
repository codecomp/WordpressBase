<?php

/**
 * Newsletter sign up function
 *
 * @param $email
 * @param $name
 * @param array $custom_fields
 */
function newsletter_signup($email, $name, $custom_fields=array()){

    $api_key = get_field('newsletter_api_key', 'options');
    $list_id = get_field('newsletter_list_id', 'options');

    if (!isset($email))
        respond_and_close(false, __('Email Address Required', 'tmp'));

    if (!check_ajax_referer( 'ajax-nonce', 'security', false ))
        respond_and_close(false, __('Security Incorrect', 'tmp'));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        respond_and_close(false, __('Email Address Invalid', 'tmp'));

    // Handle submission

    respond_and_close();
}

/**
 * Ajax hook function for newsletter forms
 */
function ajax_newsletter_signup(){
    $email = sanitize_email($_REQUEST['email-address']);
    $name  = isset($_REQUEST['full-name']) ? sanitize_text_field($_REQUEST['full-name']) : '';

    newsletter_signup( $email, $name );
}
add_action('wp_ajax_newsletter_signup', 		'ajax_newsletter_signup');
add_action('wp_ajax_nopriv_newsletter_signup', 	'ajax_newsletter_signup');