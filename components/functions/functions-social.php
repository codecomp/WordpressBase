<?php

/*********************************************************************
 * Social related actions and menus
 *********************************************************************/

/**
 * Add social links ACF options tabs
 *
 * @param $tabs
 * @return mixed
 */
function theme_options_tabs_social_links( $tabs ) {

    $tabs['Social Links'] = array(
        array (
            'name' => 'Twitter ID',
            'type' => 'text',
            'instructions' => 'The unique user id for this account',
            'required' => 0,
        ),
        array (
            'name' => 'Facebook ID',
            'type' => 'text',
            'instructions' => 'The unique user id for this account',
            'required' => 0,
        ),
        array (
            'name' => 'Instagram ID',
            'type' => 'text',
            'instructions' => 'The unique user id for this account',
            'required' => 0,
        ),
        array (
            'name' => 'Google Plus ID',
            'type' => 'text',
            'instructions' => 'The unique user id for this account',
            'required' => 0,
        ),
        array (
            'name' => 'Pinterest ID',
            'type' => 'text',
            'instructions' => 'The unique user id for this account',
            'required' => 0,
        ),
        array (
            'name' => 'Tumblr ID',
            'type' => 'text',
            'instructions' => 'The unique user id for this account',
            'required' => 0,
        ),
        array (
            'name' => 'YouTube ID',
            'type' => 'text',
            'instructions' => 'The unique user id for this account',
            'required' => 0,
        )
    );

    return $tabs;
}
add_filter( 'theme_options_tabs', 'theme_options_tabs_social_links' );

/**
 * Add social api ACF options tabs
 *
 * @param $tabs
 * @return mixed
 */
function theme_options_tabs_social_api( $tabs ) {

    $tabs['Social API'] = array(
        array (
            'name' => 'Twitter API',
            'type' => 'message',
            'instructions' => '',
        ),
        array (
            'name' => 'Twitter Consumer Key',
            'type' => 'text',
            'required' => 0,
        ),
        array (
            'name' => 'Twitter Consumer Secret',
            'type' => 'text',
            'required' => 0,
        ),
        array (
            'name' => 'Twitter Username',
            'type' => 'text',
            'required' => 0,
        ),
        array (
            'name' => 'Facebook API',
            'type' => 'message',
            'instructions' => '',
        ),
        array (
            'name' => 'Facebook App ID',
            'type' 	=> 'text',
            'required' => 0,
        ),
        array (
            'name' => 'Facebook App Secret',
            'type' 	=> 'text',
            'required' => 0,
        ),
        array (
            'name' => 'Facebook Profile ID',
            'type' 	=> 'text',
            'required' => 0,
        ),
        array (
            'name' => 'Instagram API',
            'type' => 'message',
            'instructions' => '',
        ),
        array (
            'name' => 'Instagram Client ID',
            'type' 	=> 'text',
            'required' => 0,
        ),
        array (
            'name' => 'Instagram Access Token',
            'type'	=> 'text',
            'required' => 0,
        ),
        array (
            'name' => 'YouTube API',
            'type' => 'message',
            'instructions' => '',
        ),
        array (
            'name' => 'YouTube API Key',
            'type'	=> 'text',
            'required' => 0,
        ),
        array (
            'name' => 'YouTube Username',
            'type'	=> 'text',
            'required' => 0,
        ),
    );

    return $tabs;
}
add_filter( 'theme_options_tabs', 'theme_options_tabs_social_api' );

/**
 * Global function to pull in social posts from various sources
 *
 * @param $args
 * @return array
 */
function load_social($args) {
	// Check security if running via ajax
	if ( defined('DOING_AJAX') && DOING_AJAX && !check_ajax_referer( 'ajax-nonce', 'security', false ))
		respond_and_close(false, __('Security Incorrect', 'tmp'));

	// Setup default arguments
	$defaults = array(
		'offset' 	 => 0,
		'return'	 => 'json',
		'twitter' 	 => array(),
		'instagram'  => array(),
		'facebook' 	 => array(),
		'youtube'	 => array(),
		'post_types' => array()
	);
	$args = array_replace_recursive($defaults, $args);

	// Check if we have cached version of this data
	$data = get_transient('social_data_' . $args['offset']);

	if ( $data === false ) {

		$data = array();

		if( $args['twitter'] ){
			$tweets = fetch_tweets( $args['twitter']['count'], sanitize_text_field( $args['twitter']['paged_id'] ) );
			$tweets = apply_filters('load_social_twitter', $tweets);

			$data['twitter'] = $tweets;
		}

		if( $args['instagram'] ){
			$pictures = fetch_instagrams( $args['instagram']['count'], sanitize_text_field( $args['instagram']['paged_id'] ) );
			$pictures = apply_filters('load_social_instagram', $pictures);

			$data['instagram'] = $pictures;
		}

		if( $args['facebook'] ){
			$posts = fetch_facebook( $args['facebook']['count'], sanitize_text_field( $args['facebook']['paged_id'] ) );
			$posts = apply_filters('load_social_facebook', $posts);

			$data['facebook'] = $posts;
		}

		if( $args['youtube'] ){
			$videos = fetch_youtube( $args['youtube']['page'], sanitize_text_field( $args['youtube']['yt_next'] ) );
			$videos = apply_filters('load_social_youtube', $videos);

			$data['youtube'] = $videos;
		}

		if( $args['post_types'] ){
			foreach( $args['post_types'] as $k => $v ){
				$posts = fetch_posts( $v['count'], $v['offset'], $v['post_type'] );
				$posts = apply_filters('load_social_'.$v['post_type'], $posts);

				$data[$v['post_type']] = $posts;
			}
		}

		set_transient( 'social_data_' . $args['offset'], $data, DAY_IN_SECONDS );
	}

	if( $args['return'] == 'json' )
		respond_and_close($data);

	return $data;
}

add_action('wp_ajax_load_social', 			'load_social'); // for logged in user
add_action('wp_ajax_nopriv_load_social', 	'load_social'); // if user not logged in

/**
 * Fetch Tweets from api feed defined in admin
 *
 * @param int $count
 * @param null $paged_id
 * @return array
 */
function fetch_tweets($count = 10, $paged_id = null)
{
	// auth parameters
	$api_key 	= urlencode(get_field( 'twitter_consumer_key', 'option')); // Consumer Key (API Key)
	$api_secret = urlencode(get_field( 'twitter_consumer_secret', 'option')); // Consumer Secret (API Secret)

	// Access URLs
	$auth_url = 'https://api.twitter.com/oauth2/token';
	$data_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

	$ops = array(
		'screen_name' => urlencode(get_field( 'twitter_username', 'option')),
		'count' => $count,
		'trim_user' => true,
	);

	// if we're getting anything other than page 1, set last id and increase count by 1
	// to account for duplicate tweet.
	if (isset($paged_id)) {
		$ops['max_id'] = $paged_id;
		$ops['count'] = $count+1;
	}

	// Build query string from options array
	$query = http_build_query($ops);

	// get api access token
	$api_credentials = base64_encode($api_key.':'.$api_secret);

	$auth_headers = 'Authorization: Basic '.$api_credentials."\r\n".
		'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'."\r\n";

	$auth_context = stream_context_create(
		array(
			'http' => array(
				'header' => $auth_headers,
				'method' => 'POST',
				'content'=> http_build_query(array('grant_type' => 'client_credentials', )),
			)
		)
	);

	$auth_response = json_decode(file_get_contents($auth_url, 0, $auth_context), true);
	$auth_token = $auth_response['access_token'];

	// get tweets
	$data_context = stream_context_create( array( 'http' => array( 'header' => 'Authorization: Bearer '.$auth_token."\r\n", ) ) );

	// get tweets
	$data = json_decode(file_get_contents($data_url.'?'.$query, 0, $data_context), true);

	// Our return array
	$tweets = array();

	// loop through data and build our tweets array
	foreach ($data as $post) {

		$tmp = array(
			'id'  => $post['id_str'],
			'text' => $post['text'],
			'src' => $post['entities']['media'][0]['media_url'],
			'url' => 'https://twitter.com/' . $post['user']['screen_name'] . '/status/' . $post['id_str'],
			'date' => $post['created_at']
		);

		array_push($tweets, $tmp);
	}

	// if we're getting anything other than page 1, remove first duplicate tweet
	if (isset($paged_id)) {
		array_shift($tweets);
	}

	return $tweets;
}

/**
 * Fetch Instagram images from api feed defined in admin
 *
 * @param int $count
 * @param null $paged_id
 * @return array
 */
function fetch_instagrams($count = 2, $paged_id = null)
{

	$client_id = get_field('instagram_client_id', 'option');
	$token     = get_field('instagram_access_token', 'option');

	$json_url  = 'https://api.instagram.com/v1/users/'. $client_id . '/media/recent/?access_token='. $token . '&count='. $count;

	// if we're getting anything other than page 1, set last id
	if (isset($paged_id)) {
		$json_url .= '&max_id=' . $paged_id;
	}

	// Fetch JSON
	$json_object = curl_fetch($json_url);

	$data = json_decode($json_object);

	// Set up our return array
	$grams = array();

	if( $data->meta->code == 400 || empty($data->data) ) {
		return array();
	}

	$posts = $data->data;

	// For each post grab ID (for paging), image src and link
	foreach ($posts as $post) {
		$tmp = array(
			'id'  => $post->id,
			'text' => $post->caption->text,
			'src' => $post->images->standard_resolution->url,
			'url' => $post->link,
			'date' => $post->created_time
		);

		array_push($grams, $tmp);
	}

	return $grams;
}

/**
 * Fetch Facebook posts from api feed defined in admin
 *
 * @param null $paged_id
 * @return array
 */
function fetch_facebook($count=4, $paged_id = null)
{
    $fbs = array();
	$app_id     = get_field('facebook_app_id', 'option');
	$app_secret = get_field('facebook_app_secret', 'option');
	$profile_id = get_field('facebook_profile_id', 'option');

	//Setup JSON request url
    $json_url = "https://graph.facebook.com/{$profile_id}/feed?fields=full_picture,message&limit={$count}&access_token={$app_id}|{$app_secret}";

	// If we already have a next token, use that
	if ($paged_id) {

		$json_url .= '&__paging_token=' . $paged_id;

	} else {
		// Otherwise grab a feed to get a next token, then grab the feed again using it.

		// turn our fetch into JSON
		$json_object = file_get_contents_curl($json_url);

		$posts = json_decode($json_object);

		$next = $posts->paging->next;

		$json_url = $next;

	}

	// turn our fetch into JSON
	$json_object = file_get_contents_curl($json_url);

	// Break out if the json object is not valid
	if( !is_json($json_object) )
	    return $fbs;

	$posts      = json_decode($json_object);
	$next       = $posts->paging->next;
	$token_pos  = strpos($next, '__paging_token=');
	$next       = substr($next, $token_pos+15);

	// Set up data for looping
	$data = $posts->data;

	// loop through each post and get image src, link and text
	foreach ( $data as $post ) {
		$tmp = array(
			'id' => $post->id,
			'text' => (isset($post->message)) ? $post->message : null,
			'url' => 'http://facebook.com/' . $post->id,
			'src' => (isset($post->full_picture)) ? $post->full_picture : null,
			'date' => $post->created_time
		);

		array_push($fbs, $tmp);
	}

	array_push($fbs, array('paged_id' => $next));

	// return posts
	return $fbs;
}

/**
 * Fetch Youtube videos from feed defined in admin
 *
 * @param null $page
 * @param null $yt_next
 * @return array
 */
function fetch_youtube($page = null, $yt_next = null)
{
	// Auth
	$api_key = get_field('youtube_api_key', 'option');
	$username = get_field('youtube_username', 'option');

	// Start of fetch url
	$api_start = 'https://www.googleapis.com/youtube/v3/';

	// url to grab list id
	$fetch_id_url = $api_start . 'channels?part=contentDetails&forUsername=' . $username . '&key=' . $api_key;

	// fetch list id response
	$fetch_id = json_decode(curl_fetch($fetch_id_url));

	// get uploads list id
	$list_id = $fetch_id->items[0]->contentDetails->relatedPlaylists->uploads;

	// Fetch feed
	$fetch_feed_url = $api_start . 'playlistItems?part=snippet&maxResults=2&playlistId=' . $list_id . '&key=' . $api_key;

	if ($yt_next) {

		$fetch_feed_url .= '&pageToken=' . $yt_next;

	}

	// decode response
	$feed = json_decode(curl_fetch($fetch_feed_url));

	if (isset($feed->nextPageToken)) {
		$next = $feed->nextPageToken;
	} else {
		$next = null;
	}

	// Grab videos
	$posts = $feed->items;

	// Set up our return arrray
	$videos = array();

	// loop through videos adding to temp array
	foreach ($posts as $post) {

		$tmp = array(
			'src' => $post->snippet->thumbnails->medium->url,
			'url' => 'https://www.youtube.com/watch?v=' . $post->snippet->resourceId->videoId,
			'text' => $post->snippet->title
		);

		array_push($videos, $tmp);
	}

	if (count($videos) < 2) {
		$videos = array_merge($videos, $videos);
	}

	array_push($videos, array('paging' => $next));

	// return videos array
	return $videos;
}

/**
 * Fetch posts from a defined of post types
 *
 * @param int $count
 * @param int $offset
 * @return array
 */
function fetch_posts( $count = 3, $offset = 0, $post_type = 'post' )
{
	// Set up our return array
	$posts = array();

	// Args
	$args = array('post_type' => $post_type, 'numberposts' => $count, 'offset' => $offset);

	$fetched_posts = get_posts($args);

	// For each post grab stuff
	foreach ($fetched_posts as $p) {

		$tmp = array(
			'id'    => $p->ID,
			'text'  => get_the_title($p->ID),
			'url'   => get_the_permalink($p->ID),
			'src'   => get_field('featured_image', $p->ID),
			'date'  => get_the_time('F jS, Y', $p->ID)
		);

		array_push($posts, $tmp);
	}

	return $posts;
}