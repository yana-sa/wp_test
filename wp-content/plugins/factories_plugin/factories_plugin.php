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
    $randprofit = rand(10000, 1000000);

    $fdata = [
        'post_type' => 'factories',
        'post_name' => 'factories',
        'post_title' => $ftitle,
        'post_content' => $randdesc,
        'post_status' => 'publish',
    ];
    $post_id = wp_insert_post($fdata, true);

    update_post_meta($post_id, '_monthly_profit', $randprofit);
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

function create_post_types_and_taxonomy()
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
            'register_meta_box_cb' => 'monthly_profit_box']
    );

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
            'register_meta_box_cb' => 'company_selection',]
    );

    create_taxonomy();
}

add_action('init', 'create_post_types_and_taxonomy');

function create_taxonomy()
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
        'show_ui' => false,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'show_in_quick_edit' => false,
        'query_var' => true,
        'rewrite' => ['slug' => 'company_factories']
    ]);
}

function insert_taxonomies($post_id, $post, $update)
{
    create_taxonomy();
    if ($update == true) {
        return;
    }
    if ($post->post_type == 'companies' && $post->post_status == 'publish') {
        wp_insert_term(
            $post->post_title,
            'company_factories',
            ['description' => $post->content,
                'slug' => $post->post_name,]
        );
    }
}

add_action('wp_insert_post', 'insert_taxonomies', 10, 3);

//Company selection box
function companies_selection_add_meta_box()
{
    add_meta_box('company_factories',
        'Company',
        'companies_selection_meta_box',
        'factories',
        'side');
}

add_action('add_meta_boxes', 'companies_selection_add_meta_box');

function companies_selection_meta_box()
{
    if (get_post_type() !== 'factories') {
        return;
    }
    $taxonomy = 'company_factories';
    $terms_arr = get_the_terms(get_the_ID(), $taxonomy);
    if ($terms_arr !== false) {
        $term_obj = $terms_arr[0];
        $is_checked = $term_obj->term_id;
    }

    $terms = get_terms($taxonomy, ['hide_empty' => 0]);
    echo '<div>';
    foreach ($terms as $term) {
        echo '<label id="company_factories" name="company_factories">
            <input type="radio" id="company_factories" name="company_factories" value="' . $term->name . '" ' . ((isset($is_checked) && $is_checked == $term->term_id) ? "checked" : "") . '/>' . $term->name . '<br />
            </label></br>';
    }
    echo '</div>';

    if (isset($_POST['company_factories'])) {
        wp_set_object_terms(get_the_ID(), $_POST['company_factories'], $taxonomy, false);
    }
}

add_action('edit_post_factories', 'companies_selection_meta_box', 10, 2);

//Create monthly_profit meta box
function monthly_profit_box()
{
    add_meta_box(
        'monthly_profit',
        __('Monthly Profit', 'sitepoint'),
        'monthly_profit_content',
        'factories',
        'side'
    );
}

add_action('add_meta_boxes_factories', 'monthly_profit_box');

function monthly_profit_content($post)
{
    $value = get_post_meta($post->ID, '_monthly_profit', true);
    echo '<input type="number" style="width:100%" id="monthly_profit" name="monthly_profit" value="' . $value . '">';
}

function monthly_profit_box_save($post_id)
{
    $profit = !empty($_POST['monthly_profit']) ? $_POST['monthly_profit'] : null;
    if (!isset($profit)) {
        $profit = 0;
    }
    update_post_meta($post_id, '_monthly_profit', $profit);
}

add_action('save_post', 'monthly_profit_box_save');

//Get data for companies report page
function factories_data($term)
{
    wp_reset_query();
    $args = ['post_type' => 'factories',
        'company_factories' => $term->slug,
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $total_profit = 0;
        $factories_data = [];
        while ($query->have_posts()) {
            $query->the_post();
            $monthly_profit = esc_attr(get_post_meta(get_the_ID(), '_monthly_profit', true));
            $factories_data[] = [
                'title' => get_the_title(),
                'link' => get_permalink(),
                'monthly_profit' => $monthly_profit,
            ];
            $total_profit += $monthly_profit;
        }
        return ['profit' => $total_profit,
            'data' => $factories_data];
    }
}

function monthly_profit_report_data()
{
    $report_data = [];
    $terms = get_terms([
        'taxonomy' => 'company_factories',
        'hide_empty' => false,]);

    foreach ($terms as $term) {
        $factories_data = factories_data($term);
        if ($factories_data !== null) {
            $report_data[] = [
                'title' => $term->name,
                'sum_profit' => $factories_data['profit'],
                'factories' => $factories_data['data']
            ];
        } else {
            $report_data[] = [
                'title' => $term->name,
                'sum_profit' => 0,
                'factories' => null
            ];
        }
    }
    return $report_data;
}

//Get data for company post
function company_post_data()
{
    $term = get_term_by('name', get_the_title(), 'company_factories');
    $factories_data = factories_data($term);

    if ($factories_data !== null) {
        $monthly_profit = $factories_data['profit'];
        $factories = $factories_data['data'];
    } else {
        $monthly_profit = null;
        $factories = null;
    }
    return $res = [
        'profit' => $monthly_profit,
        'factories' => $factories,
    ];
}

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

    $terms = get_terms('company_factories', ['fields' => 'ids', 'hide_empty' => false]);
    foreach ($terms as $term) {
        wp_delete_term($term, 'company_factories');
    }

    do_action('factories_plugin_deactivate');
}

register_deactivation_hook(__FILE__, 'factories_plugin_deactivate');