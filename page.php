<?php

$context = Timber::context();

Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );

