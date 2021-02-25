<?php
/*
Plugin Name: Forums
Plugin URI: http://localhost:8000/
Description: Forums plugin
Author: Unknown Yana
Author URI: http://localhost:8000
Version: 1.0.0
*/

function insert_forum()
{
    $title = 'The Forum ' . rand(1, 999);
    $randdesc = 'This is a test description ' . rand(1, 999) . '. Date: ' . date("d.m.Y");
    $randorder = rand(1, 100);

    $data = [
        'post_type' => 'forum',
        'post_name' => 'forum',
        'post_title' => $title,
        'post_content' => $randdesc,
        'post_status' => 'publish',
    ];
    $post_id = wp_insert_post($data, true);

    update_post_meta($post_id, '_order', $randorder);
}

function forums_plugin_activate()
{
    $i = 1;
    while ($i++ <= 10) {
        insert_forum();
    }
    do_action('forums_plugin_activate');
}

register_activation_hook(__FILE__, 'forums_plugin_activate');

function create_post_types()
{
    register_post_type('forum', [
            'labels' => [
                'name' => 'Forums',
                'singular_name' => 'Forum',
                'add_new' => 'Add Forum',
                'all_items' => 'All Forum',
                'edit_item' => 'Edit Forum',
                'view_item' => 'View Forum'
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'forum'],
            'capability_type' => 'post',
            'show_in_rest' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-format-chat',
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_position' => 4,
        ]
    );

    register_post_type('topic',
        [
            'hierarchical' => true,
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'topic',
                'with_front' => false],
            'capability_type' => 'post',
            'show_in_rest' => false,
            'show_in_menu' => false,
            'supports' => ['page-attributes',
                'title',
                'editor',
                'custom-fields'],
        ]
    );

    register_post_type('topic_post', [
            'labels' => [
                'name' => 'Topic posts',
                'singular_name' => 'Topic post',
                'add_new' => 'Add Topic post',
                'all_items' => 'All Topic post',
                'edit_item' => 'Edit Topic post',
                'view_item' => 'View Topic post'
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'topic_post'],
            'capability_type' => 'post',
            'show_in_rest' => false,
            'show_in_menu' => false,
            'supports' => ['title', 'editor', 'custom-fields'],
        ]
    );
}

add_action('init', 'create_post_types');

function forum_order_box()
{
    add_meta_box(
        'order',
        __('Order', 'sitepoint'),
        'forum_order_box_content',
        'forum',
        'side'
    );
}

add_action('add_meta_boxes_forum', 'forum_order_box');

function forum_order_box_content($post)
{
    $value = get_post_meta($post->ID, '_order', true);
    echo "<input type='number' style='width:95%' id='order' name='order' value='" . $value . "'>";
}

function forum_order_box_save($post_id)
{
    $order = ($_POST['order']) ? $_POST['order'] : null;
    if (!$order) {
        $order = 0;
    }

    update_post_meta($post_id, '_order', $order);
}

add_action('save_post', 'forum_order_box_save');

function topic_forum_id_box()
{
    add_meta_box(
        'forum_id',
        __('Forum ID', 'sitepoint'),
        'topic_forum_id_box_content',
        'topic',
        'side'
    );
}

add_action('add_meta_boxes_forum', 'topic_forum_id_box');

function topic_forum_id_box_content($post)
{
    $query = new WP_Query([
        'post_type' => 'topic',
        'meta_key' => '_forum_id',
        'meta_value' => $post->ID
    ]);

    while ($query->have_posts()) {
        $query->the_post();
        echo "<li>" . get_the_title() . "</li>";
    }
}

function forum_list_data()
{
    $forums = [];
    $query = new WP_Query([
        'post_type' => 'forum',
        'meta_key' => '_order',
        'orderby'  => [ 'meta_value_num' => 'ASC' ],
    ]);

    while ($query->have_posts()) {
        $query->the_post();
        $forums[] = [
            'title' => get_the_title(),
            'link' => get_permalink(),
        ];
    }

    return $forums;
}

function handle_add_new_topic()
{
    $topic = ($_POST['title']) ? $_POST['title'] : null;
    $forum_id = ($_POST['forum_id']) ? $_POST['forum_id'] : null;
    if (isset($topic) || isset($forum_id)) {
        $topic_data = [
            'post_type' => 'topic',
            'post_name' => 'topic',
            'post_title' => $topic,
            'post_status' => 'publish',
        ];

        $topic_id = wp_insert_post($topic_data);
        update_post_meta($topic_id, '_forum_id', $forum_id);
    }
}

function forums_plugin_deactivate()
{
    $query = new WP_Query([
        'post_type' => 'forum',
        'post_status' => 'publish'
    ]);

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        wp_delete_post($post_id, true);
    }

    do_action('forums_plugin_deactivate');
}

register_deactivation_hook(__FILE__, 'forums_plugin_deactivate');