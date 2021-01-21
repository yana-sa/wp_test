<?php
/*
Plugin Name: Test
Plugin URI: http://localhost:8000/test
Description: An empty plugin
Author: Unknown Yana
Author URI: http://localhost:8000
Version: 1.0.0
*/
function create_books_post_type() {
    register_post_type( 'books', [
            'labels' => [
                'name' => 'Books',
                'singular_name' => 'Book',
                'add_new' => 'Add Book',
                'all_items' => 'All Books',
                'edit_item' => 'Edit Book',
                'view_item' => 'View Book'],
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'books'),
            'capability_type'    => 'post',
            'show_in_rest' => true,
            'show_in_menu' => true,
            'taxonomies' => ['category', 'post_tag'],
            'supports' => [ 'title', 'editor' ],
            'menu_position' => 5
        ]
    );
}
add_action( 'init', 'create_books_post_type' );

function insert_book_category() {
    wp_insert_term( 'Book Category', 'category', [
            'description' => 'This is a book category',
            'slug'    => 'book-category'
        ]
    );
}
add_action( 'after_setup_theme', 'insert_book_category' );