<?php
declare(strict_types = 1);

use NatOkpe\Wp\Plugin\KeekSetup\Engine;

/* Config: Taxonomies */
return [
    'faq-topic' => [
        'object_type' => 'faq',
        'args' => [
            'label'        => _x('Topics', 'topic taxonomy general name'),
            'labels' => [
                'name' => _x('Topics', 'topic taxonomy general name'),
                'singular_name' => _x('Topic', 'topic taxonomy singular name'),
                'menu_name' => __('Topics', 'natokpe'),
                'all_items' => __('All Topics', 'natokpe'),
                'edit_item' => __('Edit Topic', 'natokpe'),
                'view_item' => __('View Topic', 'natokpe'),
                'update_item' => __('Update Topic', 'natokpe'),
                'add_new_item' => __('Add New Topic', 'natokpe'),
                'new_item_name' => __('New Topic Name', 'natokpe'),
                'parent_item' => __('Parent Topic', 'natokpe'),
                'parent_item_colon' => __('Parent Topic:', 'natokpe'),
                'search_items' => __('Search Topics', 'natokpe'),
                'popular_items' => __('Popular Topics', 'natokpe'),
                'separate_items_with_commas' => __('Separate topics with commas', 'natokpe'),
                'add_or_remove_items' => __('Add or remove topics', 'natokpe'),
                'choose_from_most_used' => __('Choose from most used topic', 'natokpe'),
                'not_found' => __('No topic found.', 'natokpe'),
                'back_to_items' => __('← Back to topics', 'natokpe'),
            ],
            'description' => __('Topics specifies roles of members of natokpe as aligned with their responsibilities within the organisation.', 'natokpe'),
            'public' => true,
            'publicly_queryable' => true,
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'show_tagcloud' => false,
            'show_in_quick_edit' => true,
            'show_admin_column' => true,
            // 'meta_box_cb' => function () {},
            // 'meta_box_sanitize_cb' => function () {},
            // 'capabilities' => [
            //     'manage_terms' => 'manage_categories',
            //     'edit_terms' => 'manage_categories',
            //     'delete_terms' => 'manage_categories',
            //     'assign_terms' => 'edit_posts',
            // ],
            'rewrite' => [
                // 'slug' => 'topic',
                'with_front' => false,
            ],
            // 'query_var' => 'topic',
            // 'default_term' => [
            //     'name' => 'Member',
            //     'slug' => 'member',
            //     'description' => 'Default topic',
            // ],
            // 'sort' => null,
            // 'args' => [],
        ],
    ],
];
