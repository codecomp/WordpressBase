<?php get_header(); ?>

<main role="main" class="<?php echo get_post_type(); ?>-archive">
    <div class="wrap">
        <?php if (have_posts()) : ?>
            <h1><?php printf(__('Search results for %s', 'tmp'), get_search_query()); ?></h1>
            <?php while (have_posts()) : the_post(); ?>

                <article>
                    <header>
                        <h1><?php the_title(); ?></h1>
                    </header>
                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink(); ?>" class="button"><?php _e('View More', 'tmp'); ?></a>
                </article>

            <?php endwhile; ?>
        <?php else: ?>
            <h1><?php printf(__('Sorry, no results for %s', 'tmp'), get_search_query()); ?></h1>
        <?php endif; ?>
    </div>
</main>

<?php template_part('pagination'); ?>

<?php get_footer(); ?>