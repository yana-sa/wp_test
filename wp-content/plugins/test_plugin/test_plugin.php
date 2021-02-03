<?php
/*
Plugin Name: Test
Plugin URI: http://localhost:8000/test
Description: An empty plugin
Author: Unknown Yana
Author URI: http://localhost:8000
Version: 1.0.0
*/
require_once 'Evaluation.php';
require_once 'ResetEvaluation.php';
global $wpdb;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Seeding books on plugin activation
function insert_books()
{
    $randtitle = 'The Book ' . rand(1, 999);
    $randdesc = 'This is a test description ' . rand(1, 999) . '. Date: ' . date("d.m.Y");
    $randrating = rand(0, 10);
    $is_top = rand(0, 1);

    $data = [
        'post_type' => 'books',
        'post_name' => 'books',
        'post_title' => $randtitle,
        'post_content' => $randdesc,
        'post_status' => 'publish',
    ];

    $post_id = wp_insert_post($data, true);

    update_post_meta($post_id, '_rating_for_books', $randrating);
    update_post_meta($post_id, '_top_for_books', $is_top);
    update_post_meta($post_id, '_book_evaluation', null);
}

function test_plugin_activate()
{
    $i = 1;
    while ($i++ <= 10) {
        insert_books();
    }
    do_action('test_plugin_activate');
}

register_activation_hook(__FILE__, 'test_plugin_activate');

function create_book_evaluation_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'book_evaluation';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		  user_id BIGINT UNSIGNED NOT NULL,
		  post_id BIGINT UNSIGNED NOT NULL,
		  action VARCHAR (50) NOT NULL,
		  UNIQUE KEY id (id),
		  
		FOREIGN KEY (user_id) REFERENCES wp_users(ID)
        ON DELETE CASCADE,
		FOREIGN KEY (post_id) REFERENCES wp_posts(ID)
		ON DELETE CASCADE
		) $charset_collate;
		";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_book_evaluation_table');

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
                'view_item' => 'View Book'
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'books'],
            'capability_type' => 'post',
            'show_in_rest' => true,
            'show_in_menu' => true,
            'taxonomies' => ['book_category'],
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_position' => 5,
            'register_meta_box_cb' => 'rating_for_books_box'
        ]
    );
}

add_action('init', 'create_books_post_type');

//Create book categories
function create_book_categories()
{
    register_taxonomy('book_category', 'books', [
        'hierarchical' => false,
        'labels' => [
            'name' => _x('Book Categories', 'taxonomy general name'),
            'singular_name' => _x('Book Category', 'taxonomy singular name'),
            'search_items' => __('Search Categories'),
            'all_items' => __('All Categories'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Category'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category Name'),
            'menu_name' => __('Book Categories')],
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => ['slug' => 'book_category']
    ]);
}

add_action('init', 'create_book_categories', 0);

//Add evaluation to book posts
add_action('wp_ajax_book_evaluation_data', [new Evaluation($wpdb), 'book_evaluation_data']);
add_action('wp_ajax_reset_book_evaluation', [new ResetEvaluation($wpdb), 'reset_book_evaluation']);

function get_book_evaluation_action()
{
    global $wpdb;
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $sql = $wpdb->get_row("SELECT action FROM wp_book_evaluation WHERE user_id = '$user_id' AND post_id = '$post_id'", ARRAY_A);
    $evaluation = $sql['action'];
        return $evaluation;
}
function script_enqueue()
{
    wp_register_script('book_likes-js', get_stylesheet_directory_uri() . '/js/book_likes.js', ['jquery']);
    wp_localize_script('book_likes-js', 'myAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);

    wp_enqueue_script('jquery-js');
    wp_enqueue_script('book_likes-js');
}

add_action('init', 'script_enqueue');

//Add meta box to books categories admin menu
function select_main_book_box($book_category)
{
    wp_reset_query();
    $args = ['post_type' => 'books',
        'book_category' => $book_category->slug
    ];

    $main_book = get_option('main_book_' . $book_category->slug);
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        echo '<div class="form-field">
                <p>Please choose the main book for this category</p>
                <label for="main_book"><b>Main book for "' . $book_category->name . '" category</b></label>
                <select name="main_book" id="main_book">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<option value="' . get_the_ID() . '" ' . ($main_book == get_the_ID() ? "selected" : "") . '>' . get_the_title() . '</option>';
        }
        echo '<option value="none">None</option></select></div>';
    }

}

add_action('book_category_edit_form_fields', 'select_main_book_box');

function select_main_book_box_save($term_id)
{
    if (isset($_POST['main_book'])) {
        $term_item = get_term($term_id, 'book_category');
        update_option('main_book_' . $term_item->slug, $_POST['main_book']);
    }
}

add_action('create_book_category', 'select_main_book_box_save');
add_action('edited_book_category', 'select_main_book_box_save');

//Shortcode to show book categories
function fetch_book_categories_shortcode()
{
    echo '<h3>Book Categories';
    $categories = get_terms('book_category');

    foreach ($categories as $category) {
        echo '<h4>' . $category->name . '</h4>';
        $main_book = get_option('main_book_' . $category->slug);
        $book = get_post($main_book);
        echo '<li><a href="' . get_permalink($book) . '">Main book: "' . get_the_title($book) . '"</a></li>';
    }
}

add_shortcode('fetched_book_categories', 'fetch_book_categories_shortcode');

//Shortcode to show recently added books
function fetch_books_shortcode()
{
    $query = new WP_Query([
        'post_type' => 'books',
        'posts_per_page' => 3,
        'meta_key' => '_rating_for_books',
        'orderby' => 'meta_value',
        'order' => 'DESC',
    ]);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $rating = get_post_meta(get_the_ID(), '_rating_for_books', true);
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a><br> Rating: <b>' . $rating . '</b></li>';
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
    $rating = !empty($_POST['rating_for_books']) ? $_POST['rating_for_books'] : null;
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

    echo '<input type="checkbox" id="top_for_books" name="top_for_books" value="1" ' . $is_top . '>
		<label for="top_for_books">Top</label>';
}

function top_for_books_box_save($post_id)
{
    $top = isset($_POST['top_for_books']) && $_POST['top_for_books'] == 1;
    update_post_meta($post_id, '_top_for_books', $top);
}

add_action('save_post', 'top_for_books_box_save');

function display_top_for_books($title)
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

//Deleting books on plugin deactivation
function test_plugin_deactivate()
{
    $query = new WP_Query(array(
        'post_type' => 'books',
        'post_status' => 'publish'
    ));

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        wp_delete_post($post_id, true);
    }

    do_action('test_plugin_deactivate');
}

register_deactivation_hook(__FILE__, 'test_plugin_deactivate');

function drop_book_evaluation_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'book_evaluation';

    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
}

register_deactivation_hook(__FILE__, 'drop_book_evaluation_table');