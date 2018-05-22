<?php get_header(); ?>

<main role="main" class="<?php echo get_post_type(); ?>-single">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article>
                <header>
                    <h1><?php the_title(); ?></h1>
                    <p><?php printf(__('Time posted: %s', 'tmp'), get_the_time()); ?></p>
                    <p><?php printf(__('This post was written by %s', 'tmp'), get_the_author()); ?></p>
                </header>
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>