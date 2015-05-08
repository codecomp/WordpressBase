<?php get_header(); ?>

	<main role="main" id="<?php echo get_post_type(); ?>-single" class="clearfix">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<article>
					<header>
						<h1><?php the_title(); ?></h1>
						<p>Time posted: <?php the_time(); ?></p>
						<p>This post was written by <?php the_author(); ?></p>
					</header>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>
		<?php endif; ?>
	</main>

<?php get_footer(); ?>