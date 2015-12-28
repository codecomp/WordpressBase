<?php/** * Add any data management or wordpress extending funcitons here *//** * UK bank holiday checker * * TODO Check for weekend dates and skip to the next weekday * * @param int $day * @param int $month * @param int $year * @return bool */function get_bh($day, $month, $year) {	//Christmas    if ($month == 12 && $day == 25)    	return true;	//Boxing day    if ($month == 12 && $day == 26)		return true;	//May Day    if ($month == 5 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) == 1) && $day <=7)        return true;    $c = floor($year/100);    $n = $year-19*floor($year/19);    $k = floor(($c-17)/25);    $i = $c-floor($c/4)-floor(($c-$k)/3)+19*$n+15;    $i = $i-30*floor($i/30);    $i = $i-floor($i/28)*(1-floor($i/28))*floor(29/($i+1))*(floor(21-$n)/11);    $j = $year+floor($year/4)+$i+2-$c+floor($c/4);    $j = $j-7*floor($j/7);    $l = $i-$j;    $m = 3+floor(($l+40)/44);    $d = $l+28-31*floor($m/4);	//Easter Monday    if ($month == $m && $day == $d + 1)        return true;	//Good Friday	if ($month == $m && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) == 5) && $day <= $d && $day > $d - 7)		return true;	if ($month == 1 && $day == 1 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) != 6) && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) != 0)){		//New Year Day		return true;	} elseif ($month == 1 && $day == 2 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) != 0) && (jddayofweek(cal_to_jd(CAL_GREGORIAN, 1, 1, $year), 0) == 0)){		//		return true;	} elseif ($month == 1 && $day == 3 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, 2, $year), 0) == 0) && (jddayofweek(cal_to_jd(CAL_GREGORIAN, 1, 1, $year), 0) == 6)){		//		return true;	}	//    if ($month == 5 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) == 1) && $day >= 25)		return true;	//    if ($month == 8 && (jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0) == 1) && $day >= 25)		return true;    return false;}/** * Convert string into non spaced alphanumeric only format fr use in unique ID's * * @param string $string * @return mixed */function escape_id($string){	$string = str_replace(' ', '-', $string);	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);	return $string;}/** * Get the current page url * * @return string */function curPageURL(){	$pageURL = 'http';	if ($_SERVER["HTTPS"] == "on") {		$pageURL .= "s";	}	$pageURL .= "://";	if ($_SERVER["SERVER_PORT"] != "80") {		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];	} else {		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];	}	return $pageURL;}/** * Return the attachment ID of a attachment from the URL (Avoid use if possible) * * @param string $image_src * @return mixed */function get_attachment_id_from_src ($image_src) {	global $wpdb;	$query 	= "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";	$id 	= $wpdb->get_var($query);	return $id;}/** * Build a fully attributed image tag from an attachment ID and attachment size (Only works for images obviously) * * @param int $attachment_id * @param string $size * @param string $class */function get_the_image_thumbnail($attachment_id, $size, $class){    //Get the image attributes for the correct sized image    $image_attributes = wp_get_attachment_image_src( $attachment_id, $size );    if( $image_attributes )        return '<img src="'.$image_attributes[0].'" alt="'.get_post_meta($attachment_id, '_wp_attachment_image_alt', true).'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" class="'.$class.'"/>';    return false;}/** * Echo image built with get_the_image_thumbnail * * @param int $attachment_id * @param string $size * @param string $class */function the_image_thumbnail($attachment_id, $size = 'full', $class = ''){    if( $image = get_the_image_thumbnail($attachment_id, $size, $class) )        echo $image;}/** * Runs get_template_part from the components/parts directory with optional passing of variables * * @param $first * @param null $second * @param array $vars */function template_part( $first, $second = null, $vars = array() ){    //If we don't need to access variables return a standard get template part    if( empty($vars) ) {        get_template_part( 'components/parts/' . $first, $second );        return;    }    //Run wordpress template part hooks    do_action( "get_template_part_{$first}", $first, $second );    //Make variables accessible in include scope    extract($vars);    //Check for files existing and hard include them    if( locate_template('components/parts/' . $first . '-' . $second . '.php') != '' )        include(locate_template('components/parts/' . $first . '-' . $second . '.php'));    elseif( locate_template('components/parts/' . $first . '.php') != '' )        include(locate_template('components/parts/' . $first . '.php'));}/** * Returns a child theme overwritable image path * * @param $file * @return string */function get_theme_image($file){    if ( file_exists(STYLESHEETPATH . '/assets/images/' . $file))        return get_stylesheet_directory_uri() . '/assets/images/' . $file;    elseif ( file_exists(TEMPLATEPATH . '/assets/images/' . $file) )        return get_template_directory_uri() . '/assets/images/' . $file;    return '';}/** * Includes a child theme overwritable SVG * Uses include instead of load_template to avoid setting of globals for SVGs * * @param $file */function the_theme_svg($file){    if ( file_exists(STYLESHEETPATH . '/assets/images/' . $file))        include(STYLESHEETPATH . '/assets/images/' . $file);    elseif ( file_exists(TEMPLATEPATH . '/assets/images/' . $file) )        include(TEMPLATEPATH . '/assets/images/' . $file);}/** * Check if a user has a specific role. * * @param int $user_id * @param string $role * @return bool */function user_has_role($user_id, $role){	$user = new WP_User($user_id);	if (in_array($role, $user->roles)) {		return true;	}	return false;}/** * Check if current user has a specific role * * @param string $role * @return bool */function is_role( $role ){	if( !is_user_logged_in() )		return false;	$user = wp_get_current_user();	if( !in_array( $role, (array) $user->roles ) )		return false;	return true;}/** * Return clean site url without www. or http:// or https:// * * @param string $url * @return mixed|string */function clean_site_url( $url = null ){	$url 	= isset($url) ? $url : get_site_url();	$find_h = '#^http(s)?://#';	$find_w = '/^www\./';	$output = preg_replace($find_h, '', $url);	$output = preg_replace($find_w, '', $output);	$output = rtrim($output, "/");	return $output;}/** * Returns a relative URL without the blog_url * * @param $url * @return mixed|string */function get_stripped_url( $url ){	$stripped_url = str_replace( get_site_url(), '', $url );	if (substr($stripped_url, 0, 1) != '/') {		return '/'.$stripped_url;	}	return $stripped_url;}/** * Formats a URL formatted for external linking with http:// included * * @param string $url * @param bool $secure * @return string */function force_url_http( $url, $secure=false ){    //Make sure we start with a clean url without http or https    $clean_url = clean_site_url($url);	if($secure){        return 'http://'.$url;    } else{        return 'https://'.$url;    }}/** * Dump and Die function. * * @param $var * @param bool $die */function dd( $var, $die=true ){    echo '<pre>';    var_dump($var);    echo '</pre>';    if( $die )        die();}/** * Debug lof unction, logs variables or messages with optional timestamp to debug.log file in template route * * @param mixed $var * @param bool $timestamp */function dlog( $var, $timestamp=true ){    $file = get_template_directory() . '/debug.log';    if( !file_exists($file) ){        $handle = fopen($file, 'w') or die('Cannot open file:  '.$file); //open file for writing ('w','r','a')...    } else{        $handle = fopen($file, 'a') or die('Cannot open file:  '.$file);    }    $content = ($timestamp ? date('Y-m-d h:i:s') . " " : '' ) . var_export($var, true) . "\n";    fwrite($handle, $content);    fclose($handle);}/** * First function deals with interpreting and formatting single hook, not really meant to be called directly. * * @param $tag * @param $hook */function dump_hook( $tag, $hook ) {	ksort($hook);	echo "<pre>>>>>>\t$tag<br>";	foreach( $hook as $priority => $functions ) {		echo $priority;		foreach( $functions as $function )			if( $function['function'] != 'list_hook_details' ) {				echo "\t";				if( is_string( $function['function'] ) )					echo $function['function'];				elseif( is_string( $function['function'][0] ) )					echo $function['function'][0] . ' -> ' . $function['function'][1];				elseif( is_object( $function['function'][0] ) )					echo "(object) " . get_class( $function['function'][0] ) . ' -> ' . $function['function'][1];				else					print_r($function);				echo ' (' . $function['accepted_args'] . ') <br>';			}	}	echo '</pre>';}/** * When called this function will output current state of all hooks in alphabetized order. If passed string as argument it will only list hooks that have that string in name. * * @param bool $filter */function list_hooks( $filter = false ){	global $wp_filter;	$hooks = $wp_filter;	ksort( $hooks );	foreach( $hooks as $tag => $hook )		if ( false === $filter || false !== strpos( $tag, $filter ) )			dump_hook($tag, $hook);}/** * Whenever hook with this function added gets executed it will output details right in place. * * @param null $input * @return null */function list_hook_details( $input = NULL ) {	global $wp_filter;	$tag = current_filter();	if( isset( $wp_filter[$tag] ) )		dump_hook( $tag, $wp_filter[$tag] );	return $input;}/** * This will list live details on all hooks or specific hook, passed as argument. * * @param bool $hook */function list_live_hooks( $hook = false ) {	if ( false === $hook )		$hook = 'all';	add_action( $hook, 'list_hook_details', -1 );}?>