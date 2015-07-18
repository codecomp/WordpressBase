<?php

/**
 * Adds a email address to a Mail Chimp list
 *
 * @param $email
 */
function mail_chimp_send( $email ){

	//TODO Test on live server, local host throwing [Uncaught exception 'Mailchimp_HttpError' with message 'API call to lists/subscribe failed: SSL certificate problem: unable to get local issuer certificate']

	//Setup the returns we will use
	$success_data 	= array('success' => true, 'message' => 'Thank you');
	$error_data 	= array('success' => false, 'message' => 'There has been an error, Please try again later');

	//Check the nonce not invalid and return the error if it is
	if( !check_ajax_referer( 'mail-chimp-ajax-nonce', 'security', false ) || !filter_var($email, FILTER_VALIDATE_EMAIL) ){
        wp_send_json($error_data);
	}

	//Include the mailchimp API
	require_once __DIR__ . '/../inc/mailchimp/Mailchimp.php';

	//Setup the API keys required for this list and client
	$api_key 	= 'client-id-here';
	$list_id 	= 'list-id-here';

	$double_optin		= true;
	$update_existing	= false;
	$replace_interests	= true;
	$send_welcome		= true;

	$Mailchimp 		    = new Mailchimp( $api_key );
	$Mailchimp_Lists 	= new Mailchimp_Lists( $Mailchimp );


	try{
		$subscriber 		= $Mailchimp_Lists->subscribe(
			$list_id,
			array(
				'email' => htmlentities( $email )
			),
			null,
			'html',
			$double_optin,
			$update_existing,
			$replace_interests,
			$send_welcome
		);

		//If everything has worked return the JSON success
        wp_send_json($success_data);
	}
	catch (Exception $e){
		//Add the error to the returned JSON
		$error_data['error'] = $e;
        wp_send_json($error_data);
	}
}

/**
 * Ajax response for adding email address to Mail Chimp list
 */
function mail_chimp_ajax(){
	/****************************************
	 *Sample jQuery ajax use of this function
	 ****************************************
        $.ajax({
            url: base_dir+"/wp-admin/admin-ajax.php",
            type:'POST',
            data: {
                action: 	'mail_chimp',
                email: 		$('email field').val(),
                security: 	'<?php echo wp_create_nonce( "mail-chimp-ajax-nonce" ); ?>'
            },
            dataType: 'json',
            success: function(data){
                //Access the returned JSON
                console.log(data.message);
            }
        });
	*/

	//Call the function to end the data
	mail_chimp_send( sanitize_email($_POST['email']) );

	//Stop further processing
	exit;
}
add_action('wp_ajax_mail_chimp', 		'mail_chimp_ajax'); // for logged in user
add_action('wp_ajax_nopriv_mail_chimp', 'mail_chimp_ajax'); // if user not logged in


/**
 * Adds a email address (and extra fields) to a Campaign monitor list
 *
 * @param $email
 * @param $name
 */
function campaign_monitor_send( $email, $name ){

	//Setup the returns we will use
	$success_data 	= array('success' => true, 'message' => 'Thank you');
	$error_data 	= array('success' => false, 'message' => 'There has been an error, Please try again later');

	//Check the nonce not invalid and return the error if it is
	if( !check_ajax_referer( 'campaign-monitor-ajax-nonce', 'security', false ) || !filter_var($email, FILTER_VALIDATE_EMAIL) ){
        wp_send_json($error_data);
	}

    //Setup the API keys required for this list and client
    $api_key 	= 'client-id-here';
    $list_id 	= 'list-id-here';

	//Include the bloated campaign monitor API
	require_once __DIR__ . '/../inc/campaignmonitor/csrest_subscribers.php';

	//If passing string for second variable it assumes it is api key and converts it into array inside class
	$wrap   = new CS_REST_Subscribers($list_id, $api_key);
	$result = $wrap->add(array(
		'EmailAddress' 	=> $email,
		'Name' 			=> $name,
		/*
		'CustomFields' => array(
			array(
				'Key' => 'Field 1 Key',
				'Value' => 'Field Value'
			),
			array(
				'Key' => 'Field 2 Key',
				'Value' => 'Field Value'
			),
			array(
				'Key' => 'Multi Option Field 1',
				'Value' => 'Option 1'
			),
			array(
				'Key' => 'Multi Option Field 1',
				'Value' => 'Option 2'
			)
		),
		*/
		'Resubscribe' => true
	));

	//Return the JSON Data depending on the result
	if($result->was_successful()) {
        wp_send_json($success_data);
	} else {
		//Add the error to the returned JSON
		$error_data['error'] = $result->response->Message;
        wp_send_json($error_data);
	}
}

/**
 * Ajax response for adding email (and extra fields) address to Campaign Monitor list
 */
function campaign_monitor_ajax(){
	/****************************************
	 *Sample jQuery ajax use of this function
	 ****************************************
        $.ajax({
            url: base_dir+"/wp-admin/admin-ajax.php",
            type:'POST',
            data: {
                action: 	'campaign_monitor',
                email: 		$('email field').val(),
                name: 		$('name field').val(),
                security: 	'<?php echo wp_create_nonce( "campaign-monitor-ajax-nonce" ); ?>'
            },
            dataType: 'json',
            success: function(data){
                //Access the returned JSON
                console.log(data.message);
            }
        });
	*/

	//Call the function to end the data
	campaign_monitor_send( sanitize_email($_POST['email']), sanitize_text_field($_POST['name']) );

	//Stop further processing
	exit;
}
add_action('wp_ajax_campaign_monitor', 			'campaign_monitor_ajax'); // for logged in user
add_action('wp_ajax_nopriv_campaign_monitor', 	'campaign_monitor_ajax'); // if user not logged in
