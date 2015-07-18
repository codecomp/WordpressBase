<?php
//Generate a GET friendly URL
$url = preg_replace( '/\?.*/', '', curPageURL() );

//Work out the page title
if( is_home() ){
	$title = get_bloginfo('name');
} elseif( is_404()){
	$title = 'Page not found';
} elseif( is_archive()){
	$title = single_cat_title('', false);
} elseif( is_single() ){
	$title = single_post_title('', false);
} else{
	$title = get_the_title();
}
?>

<ul class="social-share">
	<li>
		<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" title="Share on Facebook"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-facebook"></i><?php else: ?>Share on Facebook<?php endif; ?></a>
	</li>
	<li>
		<a href="https://twitter.com/home?status=<?php echo $url; ?> <?php echo $title; ?>" title="Share on Twitter"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-twitter"></i><?php else: ?>Share on Twitter<?php endif; ?></a>
	</li>
	<li>
		<a href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Share on Google plus"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-google-plus"></i><?php else: ?>Share on google+<?php endif; ?></a>
	</li>
	<li>
		<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>&summary=&source="<?php echo get_site_url(); ?> title="Share on LinkedIn"><?php if( wp_style_is( 'font-awesome', 'enqueued' ) ):?><i class="fa fa-linkedin"></i><?php else: ?>Share on LinkedIn<?php endif; ?></a>
	</li>
</ul>