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