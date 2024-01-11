<?php

$context = Timber::context();

$context['title'] = sprintf(__('Search results for %'), get_search_query());
$context['posts'] = new Timber\PostQuery();

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );
Timber::render( $templates, $context );
