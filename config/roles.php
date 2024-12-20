<?php
declare(strict_types = 1);

use NatOkpe\Wp\Plugin\KeekSetup\Engine;

return [
    'student' => [
        'name' => 'Student',
        'cap' => [
        ],
    ],

    'teacher' => [
        'name' => 'Teacher',
        'cap' => [
        ],
    ],

    'accountant' => [
        'name' => 'Accountant',
        'cap' => [
        ],
    ],

    'hrm' => [
        'name' => 'HRM',
        'cap' => [
        ],
    ],

    'senior_administrator' => [ // Class I
        'name' => 'Senior Administrator',
        'cap' => [
            'edit_dashboard',
            'export',
            'list_users',
            'manage_categories',
            'manage_links',
            'manage_options',
            'promote_users',
            'read',
            'create Patterns',
            'edit Patterns',
            'read Patterns',
            'delete Patterns',
            'remove_users',
            'upload_files',
            'customize',

            'add_users',
            'create_users',
            'delete_users',
            'unfiltered_html',
            'unfiltered_upload',

            'edit_position',
            'read_position',
            'delete_position',
            'edit_positions',
            'edit_others_positions',
            'delete_positions',
            'publish_positions',
            'read_private_positions',
            'delete_private_positions',
            'delete_published_positions',
            'delete_others_positions',
            'edit_private_positions',
            'edit_published_positions',
            'edit_positions',

            'edit_application',
            'read_application',
            'delete_application',
            'edit_applications',
            'edit_others_applications',
            'delete_applications',
            'publish_applications',
            'read_private_applications',
            'delete_private_applications',
            'delete_published_applications',
            'delete_others_applications',
            'edit_private_applications',
            'edit_published_applications',
            'edit_applications',

            'edit_faq',
            'read_faq',
            'delete_faq',
            'edit_faqs',
            'edit_others_faqs',
            'delete_faqs',
            'publish_faqs',
            'read_private_faqs',
            'delete_private_faqs',
            'delete_published_faqs',
            'delete_others_faqs',
            'edit_private_faqs',
            'edit_published_faqs',
            'edit_faqs',
        ],
    ],

    'junior_administrator' => [ // Class II
        'name' => 'Junior Administrator',
        'cap' => [
            'edit_dashboard',
            'list_users',
            'manage_categories',
            'manage_links',
            'read',
            'upload_files',
            'customize',
            'unfiltered_html',
            'unfiltered_upload',

            'edit_position',
            'read_position',
            'edit_positions',
            'edit_others_positions',
            'publish_positions',
            'read_private_positions',
            'delete_private_positions',
            'edit_private_positions',
            'edit_positions',

            'edit_application',
            'read_application',
            'edit_applications',
            'edit_others_applications',
            'publish_applications',
            'read_private_applications',
            'edit_private_applications',
            'edit_published_applications',
            'edit_applications',

            'edit_faq',
            'read_faq',
            'edit_faqs',
            'edit_others_faqs',
            'publish_faqs',
            'read_private_faqs',
            'edit_private_faqs',
            'edit_published_faqs',
            'edit_faqs',
        ],
    ],
];
