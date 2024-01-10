<?php

// Require Composer's auto loading file
if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require_once($composer);
}

// Initialise Timber
$timber = new Timber\Timber();
Timber::$dirname = 'layouts';

/**
 * The $function_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
$function_includes = [
    'components/functions/helpers.php',
    'components/functions/reset.php',
    'components/functions/setup.php',
    'components/functions/admin.php',
    'components/functions/theme.php',
    'components/functions/cpt.php',
    'components/functions/email.php',
    'components/functions/newsletter.php',
    'components/functions/social.php',
    'components/functions/woocommerce.php',
    'components/functions/acf.php'
];

array_walk($function_includes, function ($file) {
    locate_template($file, true, true);
});
