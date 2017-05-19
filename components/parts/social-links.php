<ul class="social-links">
	<?php if( $id = get_field('facebook_id', 'option') ): ?>
		<li class="social-links__item social-links__item--facebook">
			<a class="social-links_link" target="_blank" href="http://facebook.com/<?php echo $id; ?>/" title="Find us on Facebook">
                <?php _e('Find us on Facebook', 'tmp'); ?>
            </a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('twitter_id', 'option') ): ?>
		<li class="social-links__item social-links__item--twitter">
			<a class="social-links_link" target="_blank" href="http://twitter.com/<?php echo $id; ?>/" title="Find us on Twitter">
                <?php _e('Find us on Twitter', 'tmp'); ?>
            </a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('google_plus_id', 'option') ): ?>
		<li class="social-links__item social-links__item--google">
			<a class="social-links_link" target="_blank" href="https://plus.google.com/<?php echo $id; ?>/posts/" title="Find us on Google+">
                <?php _e('Find us on Google+', 'tmp'); ?>
            </a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('pinterest_id', 'option') ): ?>
		<li class="social-links__item social-links__item--pinterest">
			<a class="social-links_link" target="_blank" href="https://www.pinterest.com/<?php echo $id; ?>/" title="Find us on Pinterest">
                <?php _e('Find us on Pinterest', 'tmp'); ?>
            </a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('linkedin_id', 'option') ): ?>
		<li class="social-links__item social-links__item--linkedin">
			<a class="social-links_link" target="_blank" href="https://www.linkedin.com/company/<?php echo $id; ?>/" title="Find us on LinkedIn">
                <?php _e('Find us on LinkedIn', 'tmp'); ?>
            </a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('instagram_id', 'option') ): ?>
		<li class="social-links__item social-links__item--instagram">
			<a class="social-links_link" target="_blank" href="https://instagram.com/<?php echo $id; ?>/" title="Find us on Instagram">
                <?php _e('Find us on Instagram', 'tmp'); ?>
            </a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('youtube_id', 'option') ): ?>
		<li class="social-links__item social-links__item--youtube">
			<a class="social-links_link" target="_blank" href="https://www.youtube.com/user/<?php echo $id; ?>/" title="Find us on Youtube">
                <?php _e('Find us on Youtube', 'tmp'); ?>
            </a>
		</li>
	<?php endif; ?>
	<?php if( $id = get_field('tumblr_id', 'option') ): ?>
		<li class="social-links__item social-links__item--tumblr">
			<a class="social-links_link" target="_blank" href="http://<?php echo $id; ?>.tumblr.com/" title="Find us on Tumblr">
                <?php _e('Find us on Tumblr', 'tmp'); ?>
            </a>
		</li>
	<?php endif; ?>
	<li class="social-links__item social-links__item--rss">
		<a class="social-links_link" target="_blank" href="<?php echo get_site_url(); ?>/feed/" title="View our feed">
            <?php _e('View our RSS feed', 'tmp'); ?>
        </a>
	</li>
</ul>