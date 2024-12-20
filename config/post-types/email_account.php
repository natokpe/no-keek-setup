<?php
declare(strict_types = 1);

return [
    'email_account' => [
        'label'  => 'Email Account',
        'labels' => [
            'name'                     => 'Email Accounts',
            'singular_name'            => 'Email Account',
            'add_new'                  => 'Add New Email Account',
            'add_new_item'             => 'Add New Email Account',
            'edit_item'                => 'Edit Email Account',
            'new_item'                 => 'New Email Account',
            'view_item'                => 'View Email Account',
            'view_items'               => 'View Email Accounts',
            'search_items'             => 'Search Email Accounts',
            'not_found'                => 'No email accounts found',
            'not_found_in_trash'       => 'No email accounts found in Trash',
            'parent_item_colon'        => 'Parent Email Account:',
            'all_items'                => 'Email Accounts',
            'archives'                 => 'Email Account Archives',
            'attributes'               => 'Email Account Attributes',
            'insert_into_item'         => 'Insert into email account',
            'uploaded_to_this_item'    => 'Uploaded to this email account',
            'featured_image'           => 'Featured image',
            'set_featured_image'       => 'Set featured image',
            'remove_featured_image'    => 'Remove featured image',
            'use_featured_image'       => 'Use as featured image',
            'menu_name'                => 'Email Accounts',
            'filter_items_list'        => 'Filter email accounts list',
            'filter_by_date'           => 'Filter by date',
            'items_list_navigation'    => 'Email Accounts list navigation',
            'items_list'               => 'Email Accounts list',
            'item_published'           => 'Email Account published.',
            'item_published_privately' => 'Email Account published privately.',
            'item_reverted_to_draft'   => 'Email Account reverted to draft.',
            'item_trashed'             => 'Email Account trashed.',
            'item_scheduled'           => 'Email Account scheduled.',
            'item_updated'             => 'Email Account updated.',
            'item_link'                => 'Email Account Link',
            'item_link_description'    => 'A link to a email account.',
        ],
        'description'         => 'Email Accounts',
        'public'              => true,
        'hierarchical'        => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'show_in_rest'        => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-id',
        'capability_type'     => ['email_account', 'email_accounts'],
        // 'capabilities'     => [],
        'map_meta_cap'        => true,
        'supports'            => ['title'],
        'taxonomies' => [
        ],
        'has_archive' => false,
        'rewrite' => [
            'with_front' => false,
        ],
        'can_export'       => true,
        'delete_with_user' => false,
    ],
];
