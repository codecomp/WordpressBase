<?php//Remove word press version number from themeremove_action('wp_head', 'wp_generator');//Return no error upon failed login attemptadd_filter('login_errors',create_function('$a', 'return null;'));require_once('functions/functions-extended.php'); 		//Include the extended functions file for useful operatorsrequire_once('functions/functions-admin.php'); 			//Include the admin setup functionsrequire_once('functions/functions-theme.php'); 			//Include the theme setup functionsrequire_once('functions/functions-email.php'); 			//Include the email tie in functionsrequire_once('functions/functions-newsletter.php'); 		//Include the newsletter tie in functionsif ( class_exists( 'WooCommerce' ) )	require_once('functions/functions-woocommerce.php'); 	//Include the woocommerce tie in functions if activeif ( class_exists( 'acf' ) )	require_once('functions/functions-acf.php'); 			//Include the advanced custom fields functions if active//Set global id's accessible inside get_template_partsfunction before_html(){	$page_object 	= get_queried_object();	$page_id 		= get_queried_object_id();}add_action('before_html', 'before_html');?>