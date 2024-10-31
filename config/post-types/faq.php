<?php
declare(strict_types = 1);

return [
    'faq' => [
        'label'  => 'FAQs',
        'labels' => [
            'name'                     => 'FAQs',
            'singular_name'            => 'FAQ',
            'add_new'                  => 'Add New FAQ',
            'add_new_item'             => 'Add New FAQ',
            'edit_item'                => 'Edit FAQ',
            'new_item'                 => 'New FAQ',
            'view_item'                => 'View FAQ',
            'view_items'               => 'View FAQs',
            'search_items'             => 'Search FAQs',
            'not_found'                => 'No faqs found',
            'not_found_in_trash'       => 'No faqs found in Trash',
            'parent_item_colon'        => 'Parent FAQ:',
            'all_items'                => 'All FAQs',
            'archives'                 => 'FAQ Archives',
            'attributes'               => 'FAQ Attributes',
            'insert_into_item'         => 'Insert into faq',
            'uploaded_to_this_item'    => 'Uploaded to this faq',
            'featured_image'           => 'Featured image',
            'set_featured_image'       => 'Set featured image',
            'remove_featured_image'    => 'Remove featured image',
            'use_featured_image'       => 'Use as featured image',
            'menu_name'                => 'FAQs',
            'filter_items_list'        => 'Filter faqs list',
            'filter_by_date'           => 'Filter by date',
            'items_list_navigation'    => 'FAQs list navigation',
            'items_list'               => 'FAQs list',
            'item_published'           => 'FAQ published.',
            'item_published_privately' => 'FAQ published privately.',
            'item_reverted_to_draft'   => 'FAQ reverted to draft.',
            'item_trashed'             => 'FAQ trashed.',
            'item_scheduled'           => 'FAQ scheduled.',
            'item_updated'             => 'FAQ updated.',
            'item_link'                => 'FAQ Link',
            'item_link_description'    => 'A link to a faq.',
        ],
        'description'         => 'Frequently Asked Questions (FAQs)',
        'public'              => true,
        'hierarchical'        => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'menu_position'       => 20,
        'menu_icon'        => 'dashicons-editor-help',
        'capability_type'     => ['faq', 'faqs'],
        // 'capabilities'     => [],
        'map_meta_cap'        => true,
        'supports'            => [
            'title',
            'editor',
            'trackbacks',
            'author',
            'excerpt',
            'page-attributes',
            'thumbnail',
        ],
        'taxonomies' => [
            'faq-topic'
        ],
        'has_archive' => false,
        'rewrite' => [
        //     'slug' => '',
            'with_front' => false,
        ],
        // 'query_var'        => '',
        'can_export'       => true,
        'delete_with_user' => false,
    ],
];
