<?php
/*
Template Name: Template Name
*/
?>

<?php get_header(); ?>

    <main role="main">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <h2><?php the_title(); ?></h2>
                <?php the_content(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </main>

<?php get_footer(); ?>
