<?php

add_action('wp_enqueue_scripts', 'enqueue_parent_styles');
function enqueue_parent_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

function my_theme_scripts() {
    wp_enqueue_script( 'jquery-3.5.1', get_template_directory_uri() . '/js/jquery-3.5.1.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'my_theme_scripts' );