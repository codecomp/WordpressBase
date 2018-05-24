<?php

/**
 * Customises the WordPress admin footer
 */
add_filter('admin_footer_text', function () {
    echo 'Created by <a target="_blank" href="https://ahoy.co.uk" title="Visit Ahoy">Ahoy</a>. Powered by <a target="_blank" href="http://www.wordpress.org" title="Visit WordPress">WordPress</a>';
});

/**
 * Add buttons to TinyMCE toolbar
 *
 * @param $buttons
 * @return array
 */
add_filter('mce_buttons', function ($buttons) {
    $buttons[] = 'formatselect';

    return $buttons;
});

/**
 * Remove TinyMCE Buttons from mce_buttons hook
 *
 * @param $buttons
 * @return array
 */
add_filter('mce_buttons', function ($buttons) {
    $remove = array(
        'wp_adv',
        'strikethrough',
        'hr',
        'wp_more',
        'fullscreen'
    );

    return array_diff($buttons, $remove);
});

/**
 * Remove TinyMCE Buttons from mce_buttons_2 hook
 *
 * @param $buttons
 * @return array
 */
add_filter('mce_buttons_2', function ($buttons) {
    $remove = array(
        'underline',
        'justifyfull',
        'forecolor',
        '|',
        'pastetext',
        'pasteword',
        'removeformat',
        'charmap',
        'outdent',
        'indent',
        'undo',
        'alignjustify',
        'redo',
        'wp_help',
        'formatselect'
    );

    return array_diff($buttons, $remove);
});
