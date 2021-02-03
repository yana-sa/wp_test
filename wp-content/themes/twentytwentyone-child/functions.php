<?php

function enqueue_parent_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_parent_styles');

function my_theme_scripts() {
    wp_enqueue_script( 'book_likes-js', get_stylesheet_directory_uri() . '/js/book_likes.js', ['jquery'], '3.5.1');
    wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/jquery.js', ['jquery'], '3.5.1');
}
add_action( 'wp_enqueue_scripts', 'my_theme_scripts' );
