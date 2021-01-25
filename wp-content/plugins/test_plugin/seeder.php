<?php

function insert_books()
{
    $randtitle = 'The Book ' . rand(1, 99);
    $randdesc = 'This is a test description ' . rand(1, 99) . '. Date: ' . date("d.m.Y");
    $randrating = rand(0, 10);
    $is_top = rand(0, 1);

        $data = [
            'post_type' => 'books',
            'post_category' => array(6),
            'post_name' => 'books',
            'post_title' => $randtitle,
            'post_content' => $randdesc,
            'post_status' => 'publish'
        ];
    if ( !isset( $post_id ) ) {
        $post_id = wp_insert_post($data, true);
    }
    if ($post_id) {
        add_post_meta($post_id, '_rating_for_books', $randrating);
        add_post_meta($post_id, '_top_for_books', $is_top);
    }
}
