<?php

/**
 * UK bank holiday checker
 *
 * TODO Check for weekend dates and skip to the next weekday
 *
 * @param int $day
 * @param int $month
 * @param int $year
 * @return bool
 */
function get_bh($day, $month, $year)
{

    //Christmas
    if ($month == 12 && $day == 25) {
        return true;
    }

    //Boxing day
    if ($month == 12 && $day == 26) {
        return true;
    }

    //May Day
    if ($month == 5 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) == 1) && $day <= 7) {
        return true;
    }

    $c = floor($year / 100);
    $n = $year - 19 * floor($year / 19);
    $k = floor(($c - 17) / 25);
    $i = $c - floor($c / 4) - floor(($c - $k) / 3) + 19 * $n + 15;
    $i = $i - 30 * floor($i / 30);
    $i = $i - floor($i / 28) * (1 - floor($i / 28)) * floor(29 / ($i + 1)) * (floor(21 - $n) / 11);
    $j = $year + floor($year / 4) + $i + 2 - $c + floor($c / 4);
    $j = $j - 7 * floor($j / 7);
    $l = $i - $j;
    $m = 3 + floor(($l + 40) / 44);
    $d = $l + 28 - 31 * floor($m / 4);

    //Easter Monday
    if ($month == $m && $day == $d + 1) {
        return true;
    }

    //Good Friday
    if ($month == $m && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year),
                0) == 5) && $day <= $d && $day > $d - 7
    ) {
        return true;
    }

    if ($month == 1 && $day == 1 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year),
                0) != 6) && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) != 0)
    ) {
        //New Year Day
        return true;
    } elseif ($month == 1 && $day == 2 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year),
                0) != 0) && (jddayofweek(cal_to_jd(CAL_GREGORIAN, 1, 1, $year), 0) == 0)
    ) {
        //
        return true;
    } elseif ($month == 1 && $day == 3 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, 2, $year),
                0) == 0) && (jddayofweek(cal_to_jd(CAL_GREGORIAN, 1, 1, $year), 0) == 6)
    ) {
        //
        return true;
    }

    //
    if ($month == 5 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) == 1) && $day >= 25) {
        return true;
    }

    //
    if ($month == 8 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) == 1) && $day >= 25) {
        return true;
    }

    return false;
}

/**
 * Convert string into non spaced alphanumeric only format fr use in unique ID's
 *
 * @param string $string
 * @return mixed
 */
function escape_id($string)
{
    $string = str_replace(' ', '-', $string);
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

    return $string;
}

/**
 * Get the current page url
 *
 * @return string
 */
function current_page_url()
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/**
 * Return the attachment ID of a attachment from the URL (Avoid use if possible)
 *
 * @param string $image_src
 * @return mixed
 */
function get_attachment_id_from_src($image_src)
{

    global $wpdb;
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
    $id = $wpdb->get_var($query);
    return $id;

}

/**
 * Returns a child theme overwritable image path
 *
 * @param $file
 * @return string
 */
function get_theme_image($file)
{
    if (file_exists(STYLESHEETPATH . '/dist/images/' . $file)) {
        return get_stylesheet_directory_uri() . '/dist/images/' . $file;
    } elseif (file_exists(TEMPLATEPATH . '/dist/images/' . $file)) {
        return get_template_directory_uri() . '/dist/images/' . $file;
    }

    return '';
}

/**
 * Includes a child theme overwritable SVG
 * Uses file_get_contents instead of load_template to avoid setting of globals for SVGs
 *
 * @param $file
 */
function the_theme_svg($file)
{
    if ($svg = get_theme_svg($file)) {
        echo $svg;
    }
}

/**
 * Returns a child theme overwritable SVG content
 *
 * @param $file
 * @return bool|string
 */
function get_theme_svg($file)
{
    if (strpos($file, '.svg') === false) {
        $file .= '.svg';
    }

    if (file_exists(STYLESHEETPATH . '/dist/images/' . $file)) {
        return file_get_contents(STYLESHEETPATH . '/dist/images/' . $file);
    } elseif (file_exists(TEMPLATEPATH . '/dist/images/' . $file)) {
        return file_get_contents(TEMPLATEPATH . '/dist/images/' . $file);
    }

    return false;
}

/**
 * Echos a svg sprite
 *
 * @param $icon
 * @param null $class
 */
function the_icon($icon, $class = null)
{
    echo '<svg class="' . ($class ? '' . $class : '') . '"><use xlink:href="#' . $icon . '" /></svg>';
}

/**
 * Function to check if the current user is an admin
 *
 * @return boolean
 */
function is_current_user_admin() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        if (in_array('administrator', $current_user->roles, true)) {
            return true;
        }
    }
    return false;
}

/**
 * Check if a user has a specific role.
 *
 * @param int $user_id
 * @param string $role
 * @return bool
 */
function user_has_role($user_id, $role)
{
    $user = new WP_User($user_id);

    if (in_array($role, $user->roles)) {
        return true;
    }

    return false;
}

/**
 * Check if current user has a specific role
 *
 * @param string $role
 * @return bool
 */
function is_role($role)
{
    if (!is_user_logged_in()) {
        return false;
    }

    $user = wp_get_current_user();
    if (!in_array($role, (array)$user->roles)) {
        return false;
    }

    return true;
}

/**
 * Return clean site url without www. or http:// or https://
 *
 * @param string $url
 * @return mixed|string
 */
function clean_site_url($url = null)
{
    $url = isset($url) ? $url : get_site_url();
    $find_h = '#^http(s)?://#';
    $find_w = '/^www\./';
    $output = preg_replace($find_h, '', $url);
    $output = preg_replace($find_w, '', $output);
    $output = rtrim($output, "/");

    return $output;
}

/**
 * Returns a relative URL without the blog_url
 *
 * @param $url
 * @return mixed|string
 */
function get_stripped_url($url)
{
    $stripped_url = str_replace(get_site_url(), '', $url);
    if (substr($stripped_url, 0, 1) != '/') {
        return '/' . $stripped_url;
    }
    return $stripped_url;
}

/**
 * Formats a URL formatted for external linking with http:// included
 *
 * @param string $url
 * @param bool $secure
 * @return string
 */
function force_url_http($url, $secure = false)
{

    //Make sure we start with a clean url without http or https
    $clean_url = clean_site_url($url);

    if ($secure) {
        return 'http://' . $url;
    } else {
        return 'https://' . $url;
    }
}

/**
 * Encodes a string into a form usable in query strings for use with email addresses
 *
 * @param string $string
 * @return string
 */
function encode_string($string = '')
{
    return rtrim(strtr(base64_encode($string),
        'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        '6gIzTrKBW0LcEJ4eV2pRX1Zha75nOoQdbPxNFMt9m8YUkCuvSijfH3wDqylsAG'
    ), '=');
}

/**
 * Decodes string generated by encode_string
 *
 * @param string $string
 * @return string
 */
function decode_string($string = '')
{
    return base64_decode(strtr($string,
        '6gIzTrKBW0LcEJ4eV2pRX1Zha75nOoQdbPxNFMt9m8YUkCuvSijfH3wDqylsAG',
        'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
    ));
}

/**
 * Debug lof unction, logs variables or messages with optional timestamp to debug.log file in template route
 *
 * @param mixed $var
 * @param bool $timestamp
 */
function dlog($var, $timestamp = true)
{
    $file = get_template_directory() . '/debug.log';

    if (!file_exists($file)) {
        $handle = fopen($file, 'w') or die('Cannot open file:  ' . $file); //open file for writing ('w','r','a')...
    } else {
        $handle = fopen($file, 'a') or die('Cannot open file:  ' . $file);
    }

    $content = ($timestamp ? date('Y-m-d h:i:s') . " " : '') . var_export($var, true) . "\n";

    fwrite($handle, $content);
    fclose($handle);
}

/**
 * First function deals with interpreting and formatting single hook, not really meant to be called directly.
 *
 * @param $tag
 * @param $hook
 */
function dump_hook($tag, $hook)
{
    ksort($hook);

    echo "<pre>>>>>>\t$tag<br>";

    foreach ($hook as $priority => $functions) {

        echo $priority;

        foreach ($functions as $function) {
            if ($function['function'] != 'list_hook_details') {

                echo "\t";

                if (is_string($function['function'])) {
                    echo $function['function'];
                } elseif (is_string($function['function'][0])) {
                    echo $function['function'][0] . ' -> ' . $function['function'][1];
                } elseif (is_object($function['function'][0])) {
                    echo "(object) " . get_class($function['function'][0]) . ' -> ' . $function['function'][1];
                } else {
                    print_r($function);
                }

                echo ' (' . $function['accepted_args'] . ') <br>';
            }
        }
    }

    echo '</pre>';
}

/**
 * When called this function will output current state of all hooks in alphabetized order. If passed string as argument it will only list hooks that have that string in name.
 *
 * @param bool $filter
 */
function list_hooks($filter = false)
{
    global $wp_filter;

    $hooks = $wp_filter;
    ksort($hooks);

    foreach ($hooks as $tag => $hook) {
        if (false === $filter || false !== strpos($tag, $filter)) {
            dump_hook($tag, $hook);
        }
    }
}

/**
 * Whenever hook with this function added gets executed it will output details right in place.
 *
 * @param null $input
 * @return null
 */
function list_hook_details($input = null)
{
    global $wp_filter;

    $tag = current_filter();
    if (isset($wp_filter[$tag])) {
        dump_hook($tag, $wp_filter[$tag]);
    }

    return $input;
}

/**
 * This will list live details on all hooks or specific hook, passed as argument.
 *
 * @param bool $hook
 */
function list_live_hooks($hook = false)
{
    if (false === $hook) {
        $hook = 'all';
    }

    add_action($hook, 'list_hook_details', -1);
}

/**
 * Curl request wrapper
 *
 * @param $url
 * @param null $username
 * @param null $password
 * @param null $post_data
 * @return mixed
 */
function curl_fetch($url, $username = null, $password = null, $post_data = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    if ($username && $password) {
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    }

    if ($post_data) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post_data)
        ));
    }

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}

/**
 * cURL replacement for file_get_contents()
 * Safer alternative to enabling allow_url_fopen in php.ini
 *
 * @param $url
 * @return mixed
 */
function file_get_contents_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/**
 * Dumps formatted json response to user and ends processing
 *
 * @param $status
 * @param string $response
 */
function respond_and_close($status = false, $response = '')
{
    wp_send_json(array(
        'success' => $status,
        'data' => $response
    ));
}

/**
 * Check weather a string is valid JSON
 *
 * @param $string
 * @return bool
 */
function is_json($string)
{
    return is_string($string) && is_array(json_decode($string,
        true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

/**
 * Check weather expandable plugins are active in a way that can
 * be updated easily
 *
 * @param $plugin
 * @return bool
 */
function is_plugin_activated($plugin)
{
    switch ($plugin) {
        case 'woocommerce':
            return class_exists('WooCommerce');
            break;
        case 'acf':
            return class_exists('acf');
            break;
    }
    return false;
}

/**
 * Walks over a ACF field
 *
 * @param $field
 * @return array
 */
function repeater_walker($field, $callback)
{
    for ($i = 0; $i < count($field); $i++) {
        $field[$i] = call_user_func($callback, $field[$i]);
    }
    return $field;
}

/**
 * Gets a child uploaded attachment SVG from attachment ID
 * Uses file_get_contents instead of load_template to avoid setting of globals for SVGs
 *
 * @param $file
 */
function get_attachment_svg($id = null)
{
    if (!$id) {
        return '';
    }

    $src = wp_get_attachment_image_src($id, 'full');
    if ($svg = file_get_contents(str_replace(get_site_url() . '/', ABSPATH, $src[0]))) {
        return $svg;
    }

    return false;
}

/**
 * Get the source for an attachment image without needing to store to a variable each time
 *
 * @param $id
 * @param $size
 * @return bool
 */
function get_attachment_image_src($id, $size)
{
    $attachment = wp_get_attachment_image_src($id, $size);

    if ($attachment) {
        return $attachment[0];
    }

    return false;
}

/**
 * Check if the provided term has child terms
 *
 * @param $term
 * @return bool
 */
function term_has_children($term){
    $children = get_terms( $term->taxonomy, array(
        'parent'    => $term->term_id,
        'hide_empty' => false
    ) );

    if($children){
        return true;
    }

    return false;
}

/**
 * A WordPress helper function that takes the ID of the current post
 * and returns the top three related posts ranked by highest number of taxonomy
 * terms in common with the current post. Alternately, you can modify lines 26-33
 * to exclude certain taxonomies so that we only check for terms in specific taxonomies 
 * to determine the related posts. To include up to the top X related posts instead of
 * up to three, you can modify lines 149-150.
 *
 * In your template to make use of this function you would do something like...
 *
 * $current_post_id = get_the_id();
 * $related_post_ids = get_related_posts($current_post_id);
 * 
 */
function get_related_posts($current_post_id, $count = 3) {   
    // Get the post type we're dealing with based on the current post ID.
    $post_type = get_post_type($current_post_id);

    // Get all taxonomies of the specified post type of the current post.
    $taxonomies = [];
    $taxonomy_objects = get_object_taxonomies( $post_type, 'objects' );
    foreach($taxonomy_objects as $taxonomy) {
        // If you want to only check against certain taxonomies, modify this section as needed
        // to set conditions for which taxonomies should be excluded or included. Below is just an example.
        // if ($taxonomy->name !== 'post_format' && $taxonomy->name !== 'post_tag') {
        //     array_push($taxonomies, $taxonomy);
        // }
       
        // By default, we will check against all taxonomies.
        array_push($taxonomies, $taxonomy);
    }

    // Get all the posts of the specified post type,
    // excluding the current post, so that we can compare these
    // against the current post.
    $other_posts_args = array(
        'posts_per_page'   => -1,
        'post_type'      => $post_type,
        'post__not_in'   => array($current_post_id),
    );
    $other_posts = new WP_Query( $other_posts_args );

    wp_reset_postdata();

    // We will create an object for each matching post that will include
    // the ID and count of the number of times it matches any taxonomy term with the current post.
    // Later, when we create those, they will get pushed to this $matching_posts array.
    $matching_posts = array();

    // If we have other posts, loop through them and
    // count matches for any taxonomy terms in common.
    if($other_posts->have_posts()) {

        foreach($taxonomies as $taxonomy) {

            // Get the term IDs of terms for the current post
            // (the post presumably displaying as a single post
            // back in our template, for which were finding related posts).
            $current_post_terms = get_the_terms($current_post_id, $taxonomy->name);


            // Only continue if the current post actually has some terms for this taxonomy.
            if($current_post_terms !== false) {

                foreach($other_posts->posts as $post) {

                    // Get the term IDs of terms for this taxonomy
                    // for the other post we are currently looping over.
                    $other_post_terms = get_the_terms($post->ID, $taxonomy->name);

                    // Check that other post has terms and only continue if there
                    // are terms to compare.
                    if($other_post_terms !== false) {

                        $other_post_term_IDs = array();
                        $current_post_term_IDs = array();

                        // Get term IDs from each term in the current post.
                        foreach($current_post_terms as $term) {
                            array_push($current_post_term_IDs, $term->term_id);
                        }

                        // Get term IDs from each term in the other post.
                        foreach($other_post_terms as $term) {
                            array_push($other_post_term_IDs, $term->term_id);
                        }

                        if( !empty($other_post_term_IDs) && !empty($current_post_term_IDs) ) {
                             
                            // Collect the matching term IDs for the terms the posts have in common.
                            $match_count = sizeof(array_intersect($other_post_term_IDs, $current_post_term_IDs));
                             
                            // Get the ID of the other post to use to identify and store this post
                            // in our results.
                            $post_ID = $post->ID;

                            if ($match_count > 0) {

                                // Assume post not added previously.
                                $post_already_added = false;

                                // If posts have already been added to our matches
                                // then check to see if we already added this post.
                                if(!empty($matching_posts)) {
     
                                    foreach($matching_posts as $post) {
                                        // If this post was added previously then let's increment the count
                                        // for our new matching terms.
                                        if (isset($post->ID) && $post->ID == $post_ID) {
                                            $post->count += $match_count;
                                            // Switch this to true for the check we perform below.
                                            $post_already_added = true;
                                        }
                                    }
                                     
                                    // If never found a post with same ID in our $matching_posts
                                    // list then create a new entry associated with this post and add it.
                                    if ($post_already_added === false) {
                                        $new_matching_post = new stdClass();
                                        $new_matching_post->ID = $post_ID;
                                        $new_matching_post->count = $match_count;
                                        array_push($matching_posts, $new_matching_post);
                                    }
                                } else {
                                    // If no posts have been added yet to $matching_posts then this will be the first.
                                    $new_matching_post = new stdClass();
                                    $new_matching_post->ID = $post_ID;
                                    $new_matching_post->count = $match_count;
                                    array_push($matching_posts, $new_matching_post);
                                }
                            }
                        }
                    }
                }
            }
        }

         
        if(!empty($matching_posts)) {
            // Sort the array in order of highest count for total terms in common
            // (most related to least).
            usort($matching_posts, function($a, $b) {
                return strcmp($b->count, $a->count);
            });
            // Just take the top 3 most related
            $most_related = array_slice($matching_posts, 0, $count);
            
            // Get the IDs of most related posts.
            $matching_posts = array_map(function($obj) {
                return $obj->ID;
            }, $most_related);
        }

    } 

    return $matching_posts;

}