<ul class="social-links">
	<?php if( $id = get_field('facebook_id', 'option') ): ?>
		<li class="facebook">
			<a target="_blank" href="http://facebook.com/<?php echo $id; ?>/" title="Find us on Facebook"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-facebook"></i><?php else: ?>Find us on Facebook<?php endif; ?></a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('twitter_id', 'option') ): ?>
		<li class="twitter">
			<a target="_blank" href="http://twitter.com/<?php echo $id; ?>/" title="Find us on Twitter"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-twitter"></i><?php else: ?>Find us on Twitter<?php endif; ?></a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('google_plus_id', 'option') ): ?>
		<li class="google">
			<a target="_blank" href="https://plus.google.com/<?php echo $id; ?>/posts/" title="Find us on Google+"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-google-plus"></i><?php else: ?>Find us on Google+<?php endif; ?></a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('pinterest_id', 'option') ): ?>
		<li class="pinterest">
			<a target="_blank" href="https://www.pinterest.com/<?php echo $id; ?>/" title="Find us on Pinterest"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-pinterest"></i><?php else: ?>Find us on Pinterest<?php endif; ?></a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('linkedin_id', 'option') ): ?>
		<li class="linkedin">
			<a target="_blank" href="https://www.linkedin.com/company/<?php echo $id; ?>/" title="Find us on LinkedIn"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-linkedin "></i><?php else: ?>Find us on LinkedIn<?php endif; ?></a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('instagram_id', 'option') ): ?>
		<li class="instagram">
			<a target="_blank" href="https://instagram.com/<?php echo $id; ?>/" title="Find us on Instagram"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-instagram "></i><?php else: ?>Find us on Instagram<?php endif; ?></a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('youtube_id', 'option') ): ?>
		<li class="youtube">
			<a target="_blank" href="https://www.youtube.com/user/<?php echo $id; ?>/" title="Find us on Youtube"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-youtube "></i><?php else: ?>Find us on Youtube<?php endif; ?></a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('tumblr_id', 'option') ): ?>
		<li class="tumblr">
			<a target="_blank" href="http://<?php echo $id; ?>.tumblr.com/" title="Find us on Tumblr"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-tumblr "></i><?php else: ?>Find us on Tumblr<?php endif; ?></a>
		</li>
	<?php endif; ?>
	<li class="rss">
		<a target="_blank" href="<?php echo get_site_url(); ?>/feed/" title="View our feed"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-rss"></i><?php else: ?>View our RSS feed<?php endif; ?></a>
	</li>
</ul>