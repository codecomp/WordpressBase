<?php do_action('before_html'); ?><!DOCTYPE html><!--[if IE 7]><html class="ie ie7" <?php language_attributes(); ?>><![endif]--><!--[if IE 8]><html class="ie ie8" <?php language_attributes(); ?>><![endif]--><!--[if !(IE 7) | !(IE 8)  ]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]--><head>	<meta http-equiv="X-UA-Compatible" content="IE=edge">	<meta charset="<?php bloginfo('charset'); ?>"/>	<title><?php wp_title(); ?></title>	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri()?>/assets/faviconsapple-touch-icon-57x57.png">    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri() ?>/assets/favicons/apple-touch-icon-60x60.png">    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri() ?>/assets/favicons/apple-touch-icon-72x72.png">    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri() ?>/assets/favicons/apple-touch-icon-76x76.png">    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri() ?>/assets/favicons/apple-touch-icon-114x114.png">    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri() ?>/assets/favicons/apple-touch-icon-120x120.png">    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri() ?>/assets/favicons/apple-touch-icon-144x144.png">    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri() ?>/assets/favicons/apple-touch-icon-152x152.png">    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri() ?>/assets/favicons/apple-touch-icon-180x180.png">    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() ?>/assets/favicons/favicon-32x32.png" sizes="32x32">    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() ?>/assets/favicons/favicon-194x194.png" sizes="194x194">    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() ?>/assets/favicons/favicon-96x96.png" sizes="96x96">    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() ?>/assets/favicons/android-chrome-192x192.png" sizes="192x192">    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() ?>/assets/favicons/favicon-16x16.png" sizes="16x16">    <link rel="manifest" href="<?php echo get_template_directory_uri() ?>/assets/favicons/manifest.json">    <link rel="mask-icon" href="<?php echo get_template_directory_uri() ?>/assets/favicons/safari-pinned-tab.svg" color="#5bbad5">    <meta name="msapplication-TileColor" content="#2d89ef">    <meta name="msapplication-TileImage" content="/mstile-144x144.png">    <meta name="theme-color" content="#ffffff">	<!--[if (gte IE 6)&(lte IE 8)]>	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/fallback/selectivizr.min.js"></script>	<![endif]-->	<!--[if lt IE 9]>	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/fallback/html5.js" type="text/javascript"></script>	<![endif]-->	<!-- Mobile resize fix -->	<meta content="True" name="HandheldFriendly">	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">	<!-- end Mobile resize fix -->	<!-- Header Scripts -->	<?php get_template_part('scripts', 'header'); ?>	<!-- End Header Scripts -->	<?php wp_head(); ?></head><body <?php body_class(); ?>>	<div class="site-wrap">		<header role="banner">		</header>		<div class="page-wrap">