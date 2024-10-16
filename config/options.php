<?php
declare(strict_types = 1);

use NatOkpe\Wp\Plugin\KeekSetup\Engine;

// $pgs = get_pages([
//     'sort_order'  => 'ASC',
//     'sort_column' => 'post_title', // post_title | ID
//     'post_status' => ['publish'],
// ]);

// $pgs  = ! is_array( $pgs ) ? [] : $pgs;
// $opgs = [];

// foreach ($pgs as $__) {
//     $opgs[ $__->ID ] = ( string ) $__->ID . ' :: ' . $__->post_title;
// }

$email_accounts  = [];
$email_templates = [];

foreach ((new WP_Query(['post_type' => 'email_account']))->posts as $ac) {
    $email_accounts[$ac->ID] = $ac->post_title;
}

foreach ((new WP_Query(['post_type' => 'email_template']))->posts as $tpl) {
    $email_templates[$tpl->ID] = $tpl->post_title;
}

/* Config: Options */
$options = [
    // 'general' => [
    //     'title'       => esc_html__('Configuration', 'natokpe'),
    //     'option_key'  => 'no_conf', // The option key and admin menu page slug.
    //     'capability'  => 'manage_options', // Cap required to view options-page.
    //     'tab_group'   => 'no_keek',
    //     'tab_title'   => 'General',
    //     'position'    => 100, // Menu position. Only applicable if 'parent_slug' is left empty.
    //     // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
    //     // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
    //     'save_button'     => esc_html__('Save General Settings', 'natokpe' ), // The text for the options-page save button. Defaults to 'Save'.
    //     // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
    //     // 'message_cb'      => 'yourprefix_options_page_message_callback',
    //     // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
    //     'menu_title'              => esc_html__( 'Configuration', 'natokpe' ), // Falls back to 'title' (above).
    //     // 'priority'                => 10, // Define the page-registration admin menu hook priority.
    //     // 'autoload'                => false, // Defaults to true, the options-page option will be autloaded.

    //     'page-sections' => [
    //         // 'no_so_t' => [
    //         //     'name' => 'Intro',
    //         //     // 'desc' => '',

    //         //     'fields' => [
    //         //         'desc' => [
    //         //             'name' => __('Description', 'natokpe'),
    //         //             'desc' => __('Briefly describe the template.', 'natokpe'),
    //         //             'type' => 'textarea',
    //         //         ],
    //         //     ],
    //         // ],
    //     ],
    // ],

    'email' => [
        'title'       => esc_html__('Email Settings', 'natokpe'),
        'option_key'  => 'email-settings', // The option key and admin menu page slug.
        'parent_slug' => 'admin.php?page=no_conf', // 'no_conf', // 'themes.php' Make options page a submenu item of the themes menu.
        'capability'  => 'manage_options', // Cap required to view options-page.
        'tab_group'   => 'email-settings',
        'tab_title'   => 'General Settings',
        'position'    => 100, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        'save_button'     => esc_html__('Save Changes', 'natokpe' ), // The text for the options-page save button. Defaults to 'Save'.
        // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
        // 'message_cb'      => 'yourprefix_options_page_message_callback',
        // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        'menu_title'              => esc_html__( 'Email Settings', 'natokpe' ), // Falls back to 'title' (above).
        // 'priority'                => 10, // Define the page-registration admin menu hook priority.
        // 'autoload'                => false, // Defaults to true, the options-page option will be autloaded.

        'page-sections' => [
            'endis_email' => [
                'name' => 'Enable / Disable Email',
                'desc' => 'Settings to allow or disallow email sending accross site. ',

                'fields' => [
                    'allow_email' => [
                        'name' => 'Enable Email',
                        'type' => 'checkbox',
                        'desc' => 'Check to allow sending emails from site.',
                    ],
                ],
            ],
        ],
    ],

    'email-sending-options' => [
        'title'       => esc_html__('Email Settings', 'natokpe'),
        'option_key'  => 'email-sending-options', // The option key and admin menu page slug.
        'parent_slug' => 'admin.php?page=no_conf', // 'no_conf', // 'themes.php' Make options page a submenu item of the themes menu.
        'capability'  => 'manage_options', // Cap required to view options-page.
        'tab_group'   => 'email-settings',
        'tab_title'   => 'Sending Options',
        // 'position'    => 100, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        'save_button'     => esc_html__('Save Changes', 'natokpe' ), // The text for the options-page save button. Defaults to 'Save'.
        // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
        // 'message_cb'      => 'yourprefix_options_page_message_callback',
        // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        'menu_title'              => esc_html__( 'Email Settings', 'natokpe' ), // Falls back to 'title' (above).
        // 'priority'                => 10, // Define the page-registration admin menu hook priority.
        // 'autoload'                => false, // Defaults to true, the options-page option will be autloaded.

        'page-sections' => [
            'login_notif' => [
                'name' => 'Login Notification',
                'desc' => 'Notification emails sent to user after a successful login',

                'fields' => [
                    'emsd_stnf_ac' => [
                        'name' => 'Email Account',
                        'type' => 'select',
                        'desc' => 'Email account to use in sending this email type',
                        'options' => ['default' => 'Default'] + $email_accounts,
                    ],

                    'emsd_stnf_tpl' => [
                        'name' => 'Template',
                        'type' => 'select',
                        'desc' => 'Email template to use for this type.',
                        'options' => ['default' => 'Default'] + $email_templates,
                    ],

                    'emsd_stnf_mode' => [
                        'name' => 'Activation Mode',
                        'type' => 'select',
                        // 'desc' => '',
                        'options_cb' => function () {
                            return ['default' => 'Default', 'instant' => 'Instant', 'worker' => 'Worker'];
                        }
                    ]
                ],
            ],

            'su_confirm' => [
                'name' => 'Signup Confirmation',
                'desc' => 'Confirmation emails send to new student user after sign up.',

                'fields' => [
                    'emsd_stcf_ac' => [
                        'name' => 'Email Account',
                        'type' => 'select',
                        'desc' => 'Email account to use in sending sign up confirmation emails.',
                        'options' => ['default' => 'Default'] + $email_accounts,
                    ],

                    'emsd_stcf_tpl' => [
                        'name' => 'Template',
                        'type' => 'select',
                        'desc' => 'Email template to use for this type.',
                        'options' => ['default' => 'Default'] + $email_templates,
                    ],

                    'emsd_stcf_mode' => [
                        'name' => 'Activation Mode',
                        'type' => 'select',
                        // 'desc' => '',
                        'options_cb' => function () {
                            return ['default' => 'Default', 'instant' => 'Instant', 'worker' => 'Worker'];
                        }
                    ]
                ],
            ],

            'email_ver' => [
                'name' => 'Email Verification',
                'desc' => 'Email message containing verification details sent to user to verify email address ownership.',

                'fields' => [
                    'email_ver_ac' => [
                        'name' => 'Email Account',
                        'type' => 'select',
                        'desc' => 'Email account to use in sending sign up confirmation emails.',
                        'show_option_none' => true,
                        'options' => $email_accounts,
                    ],

                    'email_ver_tpl' => [
                        'name' => 'Template',
                        'type' => 'select',
                        'desc' => 'Email template to use for this type.',
                        'show_option_none' => true,
                        'options' => $email_templates,
                    ],

                    'email_ver_mode' => [
                        'name' => 'Activation Mode',
                        'type' => 'select',
                        // 'desc' => '',
                        'default' => 'worker',
                        'show_option_none' => true,
                        'options_cb' => function () {
                            return ['instant' => 'Instant', 'worker' => 'Worker'];
                        }
                    ],

                    'email_ver_allow' => [
                        'name' => 'Enable',
                        'type' => 'checkbox',
                        'desc' => 'Check to allow this email type to be sent',
                    ],
                ],
            ],
        ],
    ],

    'signon' => [
        'title'       => esc_html__('Configuration', 'natokpe'),
        'option_key'  => 'no_conf_signon', // The option key and admin menu page slug.
        'parent_slug' => 'admin.php?page=no_conf', // 'no_conf', // 'themes.php' Make options page a submenu item of the themes menu.
        'capability'  => 'manage_options', // Cap required to view options-page.
        'tab_group'   => 'no_keek',
        'tab_title'   => 'Login and Registration',
        'position'    => 100, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        'save_button'     => esc_html__('Save Changes', 'natokpe' ), // The text for the options-page save button. Defaults to 'Save'.
        // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
        // 'message_cb'      => 'yourprefix_options_page_message_callback',
        // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        'menu_title'              => esc_html__( 'Login and Registration', 'natokpe' ), // Falls back to 'title' (above).
        // 'priority'                => 10, // Define the page-registration admin menu hook priority.
        // 'autoload'                => false, // Defaults to true, the options-page option will be autloaded.

        'page-sections' => [
        ],
    ],

    'pages' => [
        'title'       => esc_html__('Configuration', 'natokpe'),
        'option_key'  => 'no_conf_pages', // The option key and admin menu page slug.
        'parent_slug' => 'admin.php?page=no_conf', // 'no_conf', // 'themes.php' Make options page a submenu item of the themes menu.
        'capability'  => 'manage_options', // Cap required to view options-page.
        'tab_group'   => 'no_keek',
        'tab_title'   => 'Pages',
        'position'    => 100, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        'save_button'     => esc_html__('Save Changes', 'natokpe' ), // The text for the options-page save button. Defaults to 'Save'.
        // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
        // 'message_cb'      => 'yourprefix_options_page_message_callback',
        // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        'menu_title'              => esc_html__( 'Page Locations', 'natokpe' ), // Falls back to 'title' (above).
        // 'priority'                => 10, // Define the page-registration admin menu hook priority.
        // 'autoload'                => false, // Defaults to true, the options-page option will be autloaded.

        'page-sections' => [
        ],
    ],
];

return $options;
