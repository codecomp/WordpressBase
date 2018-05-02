<?php

/*********************************************************************
 * Newsletter subscriptions
 *********************************************************************/

/**
 * Newsletter sign up function for Campaign monitor and Mail chimp
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

    $is_mailchimp = strpos('-', $api_key);

    if ( !$is_mailchimp ) {
        // CampaignMonitor

        $fields = array(
            'EmailAddress' 	=> $email,
            'Name' 			=> $name,
            'Resubscribe'   => true,
            'customFields'  => $custom_fields
        );

        $api = 'https://api.createsend.com/api/v3.1/subscribers/' . $list_id .'.json?pretty=true';
    } else {
        // MailChimp
        $name = explode(' ', $name);

        $fields = array(
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields' => array(
                'FNAME' => $name[0],
                'LNAME' => isset($name[1]) ? $name[1] : ''
            ),
        );

        foreach( $custom_fields as $k => $v ){
            $fields['merge_fields'][$k] =$v;
        }

        $api_region = explode('-', $api_key);

        $api = 'https://' . $api_region[1] . '.api.mailchimp.com/3.0/lists/' . $list_id .'/members/';
    }

    $json_data = json_encode($fields);

    if ( !$is_mailchimp )
        $response = curl_fetch($api, $api_key, 'x', $json_data);
    else
        $response = curl_fetch($api, 'x', $api_key, $json_data);

    respond_and_close(true, $response);
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