<?php get_header(); ?>

<main role="main" class="<?php echo get_post_type(); ?>-archive">
    <?php if (have_posts()) : ?>
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
        <?php if($post_type = get_post_type_object( get_query_var('post_type') )): ?>
            <h2><?php printf(__('Sorry, no %s found', 'tmp'), $post_type->labels->name); ?></h2>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php template_part('pagination'); ?>

<?php get_footer(); ?>
