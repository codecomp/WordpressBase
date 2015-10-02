<?php get_header(); ?>

    <main role="main" id="<?php echo get_post_type(); ?>-archive" class="clearfix">
        <div class="wrap">
            <?php if (have_posts()) : ?>
                <h2><?php printf(__('Search results for %s', 'tmp'), get_search_query()); ?></h2>
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
                <h2><?php printf(__('Sorry, no results for %s', 'tmp'), get_search_query()); ?></h2>
            <?php endif; ?>
        </div>
    </main>

    <?php
    if(have_posts()) :
        global $wp_query;
        $big = 999999999;
        $paginate_links = paginate_links(array(
            'base'      => str_replace($big, '%#%', get_pagenum_link($big)),
            'current'   => max(1, get_query_var('paged')),
            'total'     => $wp_query->max_num_pages,
            'mid_size'  => 2,
            'prev_next' => true,
            'prev_text' => __('Previous', 'tmp'),
            'next_text' => __('Next', 'tmp')
        ));
        if ($paginate_links) {
            echo '<nav class="pagination">';
            echo $paginate_links;
            echo '</nav>';
        }
    endif;
    ?>

<?php get_footer(); ?>