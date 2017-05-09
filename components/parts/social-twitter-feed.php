<?php
//Get cached tweets
$tweets = get_transient('social_twitter_feed');

//If cache has expired/not found get the tweets
if( $tweets === false ){

	require_once (get_template_directory().'/inc/codebird/codebird.php');
	\Codebird\Codebird::setConsumerKey(get_field('twitter_consumer_key', 'option'), get_field('twitter_consumer_secret', 'option')); // static, see 'Using multiple Codebird instances'

	$cb 	= \Codebird\Codebird::getInstance();
	$cb->setToken(get_field('twitter_access_token', 'option'), get_field('twitter_access_secret', 'option'));

	//Get our tweets
	$tweets = (array) $cb->statuses_userTimeline(array('count'=>5));

	//Cache the tweets
	set_transient('social_twitter_feed', $tweets, 60*60*12);
}

?>
<div class="social-feed twitter">
	<?php foreach($tweets as $tweet): ?>

		<?php if($tweet->id ): ?>
			<div class="single tweet">
				<?php
				$t_content = $tweet->text;
				$t_content = preg_replace("/(https{0,1}:\/\/([\w\-\.\/#?&=]*))/", "<a href=\"$1\" target=\"_blank\">$2</a>", $t_content);
				$t_content = preg_replace("/@([a-z_0-9]+)/i", "<a href=\"http://twitter.com/$1\" target=\"_blank\">$0</a>", $t_content);
				$t_content = preg_replace("/#([a-z_0-9]+)/i", "<a href=\"http://twitter.com/search/$1\" target=\"_blank\">$0</a>", $t_content);
				?>
				<a class="author" href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank">@<?php echo $tweet->user->name; ?></a>
				<p class="tweet"><?php echo $t_content; ?></p>
				<div class="tweet-controls">
					<p class="post"><?php echo human_time_diff( $tweet->created_at ); ?></p>
					<a class="replyIcon" href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet->id; ?>" target="_blank"><?php _e('Reply', 'tmp'); ?></a>
					<a class="retweetIcon" href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet->id;  ?>" target="_blank"><?php _e('Retweet', 'tmp'); ?>'</a>
					<a class="faveIcon" href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet->id; ?>" target="_blank"><?php _e('Favourite', 'tmp'); ?>'</a>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>