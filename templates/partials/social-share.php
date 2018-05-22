<?php
//Generate a GET friendly URL
$url = preg_replace( '/\?.*/', '', current_page_url() );

//Work out the page title
if( is_home() ){
	$title = get_bloginfo('name');
} elseif( is_404()){
	$title = __('Page not found', 'tmp');
} elseif( is_archive()){
	$title = single_cat_title('', false);
} elseif( is_single() ){
	$title = single_post_title('', false);
} else{
	$title = get_the_title();
}
?>

<ul class="social-share js-share">
	<li class="social-share__item social-share__item--facebook">
		<a class="social-share__link" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" title="Share on Facebook">
            <?php _e('Share on Facebook', 'tmp'); ?>
        </a>
	</li>
	<li class="social-share__item social-share__item--twitter">
		<a class="social-share__link" href="https://twitter.com/home?status=<?php echo $url; ?> <?php echo $title; ?>" title="Share on Twitter">
            <?php _e('Share on Twitter', 'tmp'); ?>
        </a>
	</li>
	<li class="social-share__item social-share__item--google">
		<a class="social-share__link" href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Share on Google plus">
            <?php _e('Share on google+', 'tmp'); ?>
        </a>
	</li>
	<li class="social-share__item social-share__item--linked-in">
		<a class="social-share__link" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>&summary=&source="<?php echo get_site_url(); ?> title="Share on LinkedIn">
            <?php _e('Share on LinkedIn', 'tmp'); ?>
        </a>
	</li>
</ul>