<?php
/*
Plugin Name: Test
Plugin URI: http://localhost:8000/test
Description: An empty plugin
Author: Unknown Yana
Author URI: http://localhost:8000
Version: 1.0.0
*/

//Book post type
function create_books_post_type()
{
    register_post_type('books', [
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
            'capability_type' => 'post',
            'show_in_rest' => true,
            'show_in_menu' => true,
            'taxonomies' => ['category', 'post_tag'],
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_position' => 5,
            'register_meta_box_cb' => 'rating_for_books_box'
        ]
    );
}

add_action('init', 'create_books_post_type');

//Book Category
function insert_book_category()
{
    wp_insert_term('Book Category', 'category', [
            'description' => 'This is a book category',
            'slug' => 'book-category'
        ]
    );
}

add_action('after_setup_theme', 'insert_book_category');

//Shortcode to show recently added books
function fetch_books_shortcode()
{
    $query = new WP_Query([
        'post_type' => 'books',
        'posts_per_page' => 3
    ]);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }
    } else {
        echo 'Not found';
    }
    wp_reset_postdata();
}

add_shortcode('fetched_books', 'fetch_books_shortcode');

//Add 'Rating' custom field to Books
function rating_for_books_box()
{
    add_meta_box(
        'rating_for_books',
        __('Rating', 'sitepoint'),
        'rating_for_books_content'
    );
}

add_action('add_meta_boxes_books', 'rating_for_books_box');

function rating_for_books_content($post)
{
    $value = get_post_meta($post->ID, '_rating_for_books', true);
    echo '<textarea style="width:100%" id="rating_for_books" name="rating_for_books">' . $value . '</textarea>';
}

function rating_for_books_box_save($post_id)
{
    $rating = $_POST['rating_for_books'];
    if (!isset($rating)) {
        $rating = 0;
    }
    update_post_meta($post_id, '_rating_for_books', $rating);
}

add_action('save_post', 'rating_for_books_box_save');

//Add 'Top' custom field to Books
function top_for_books_box()
{
    add_meta_box(
        'top_for_books',
        __('Top', 'sitepoint'),
        'top_for_books_content'
    );
}

add_action('add_meta_boxes_books', 'top_for_books_box');

function top_for_books_content($post)
{
    $value = get_post_meta($post->ID, '_top_for_books', true);
    $is_top = ((int)$value == 1) ? 'checked' : '';

    echo '<input type="checkbox" id="top_for_books" name="top_for_books" value="1"' . $is_top . '>
          <label for="top_for_books">Top</label>';
}

function top_for_books_box_save($post_id)
{
    $top = isset($_POST['top_for_books']) && $_POST['top_for_books'] == 1;
    update_post_meta($post_id, '_top_for_books', $top);
}

add_action('save_post', 'top_for_books_box_save');

function display_top_for_books ($title)
{
    global $post;
    $top_for_books = esc_attr(get_post_meta($post->ID, '_top_for_books', true));

    if (($top_for_books == '1') && !is_admin()) {
        $title = '&#11088' . $title;
        return $title;
    }
    return $title;
}

add_filter('the_title', 'display_top_for_books');