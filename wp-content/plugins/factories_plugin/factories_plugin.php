<?php
/*
Plugin Name: Factories
Plugin URI: http://localhost:8000/
Description: Factories plugin
Author: Unknown Yana
Author URI: http://localhost:8000
Version: 1.0.0
*/

//Seeding data on plugin activation
function insert_factory()
{
    $ftitle = 'The Factory ' . rand(1, 999);
    $randdesc = 'This is a test description ' . rand(1, 999) . '. Date: ' . date("d.m.Y");

    $fdata = [
        'post_type' => 'factories',
        'post_name' => 'factories',
        'post_title' => $ftitle,
        'post_content' => $randdesc,
        'post_status' => 'publish',
    ];
    wp_insert_post($fdata, true);
}

function insert_company()
{
    $ctitle = 'The Company ' . rand(1, 999);
    $randdesc = 'This is a test description ' . rand(1, 999) . '. Date: ' . date("d.m.Y");

    $cdata = [
        'post_type' => 'companies',
        'post_name' => 'companies',
        'post_title' => $ctitle,
        'post_content' => $randdesc,
        'post_status' => 'publish',
    ];
    wp_insert_post($cdata, true);
}

function factories_plugin_activate()
{
    $i = 1;
    while ($i++ <= 10) {
        insert_factory();
        insert_company();
    }
    do_action('factories_plugin_activate');
}

register_activation_hook(__FILE__, 'factories_plugin_activate');

function create_factories_post_type()
{
    register_post_type('factories', [
            'labels' => [
                'name' => 'Factories',
                'singular_name' => 'Factory',
                'add_new' => 'Add Factory',
                'all_items' => 'All Factories',
                'edit_item' => 'Edit Factory',
                'view_item' => 'View Factory'
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'factories'],
            'capability_type' => 'post',
            'show_in_rest' => true,
            'show_in_menu' => true,
            'taxonomies' => ['companies'],
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_position' => 5,
        ]
    );
}

add_action('init', 'create_factories_post_type');

function create_companies_post_type()
{
    register_post_type('companies', [
            'labels' => [
                'name' => 'Companies',
                'singular_name' => 'Company',
                'add_new' => 'Add Company',
                'all_items' => 'All Companies',
                'edit_item' => 'Edit Company',
                'view_item' => 'View Company'
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'companies'],
            'capability_type' => 'post',
            'show_in_rest' => true,
            'show_in_menu' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_position' => 5,
        ]
    );
}

add_action('init', 'create_companies_post_type');

function create_company_taxonomy()
{
    register_taxonomy('company_factories', 'factories', [
        'hierarchical' => false,
        'labels' => [
            'name' => _x('Company`s Factories', 'taxonomy general name'),
            'singular_name' => _x('Company`s Factories', 'taxonomy singular name'),
            'search_items' => __('Search Company'),
            'all_items' => __('All Companies'),
            'edit_item' => __('Edit Company'),
            'update_item' => __('Update Company'),
            'add_new_item' => __('Add New Company'),
            'new_item_name' => __('New Company Name'),
            'menu_name' => __('Company`s Factories')],
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => ['slug' => 'company_factories']
    ]);

    $query = new WP_Query([
        'post_type' => 'companies',
    ]);

    while ($query->have_posts()) {
        $query->the_post();
        wp_insert_term(
            get_the_title(),
            'company_factories',
            [
                'description' => get_the_content(),
                'slug' => get_post_field('post_name', get_the_ID())
            ]
        );
    }
}

add_action('init', 'create_company_taxonomy', 0);

function select_one_company()
{
    $term_id = get_queried_object_id();
    $post_id = get_the_ID();
    $taxonomy = 'company_factories';
    $term_item = get_term($term_id, $taxonomy);
    if (isset($term_item)) {
        unset($term_item);
    }
    do_action( 'add_term_relationship', $post_id, $term_id, $taxonomy );
};

add_action('added_term_relationship', 'select_one_company');

//Deleting data on plugin deactivation
function factories_plugin_deactivate()
{
    $query = new WP_Query(array(
        'post_type' => ['factories', 'companies'],
        'post_status' => 'publish'
    ));

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        wp_delete_post($post_id, true);
    }

    do_action('factories_plugin_deactivate');
}

register_deactivation_hook(__FILE__, 'factories_plugin_deactivate');