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

/* Config: Options */
$options = [
    'no_so' => [
        'title'       => esc_html__('Sign-on Settings', 'natokpe'),
        'option_key'  => 'no_so',
        'parent_slug' => 'no_',
        'capability'  => 'manage_options',
        'tab_group'   => 'no_impact',
        'tab_title'   => 'Sign-on',

        // 'position'        => 20, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        'save_button'     => esc_html__('Save', 'natokpe' ), // The text for the options-page save button. Defaults to 'Save'.
        // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
        // 'message_cb'      => 'yourprefix_options_page_message_callback',

        'page-sections' => [
            'no_so_t' => [
                // 'name' => 'Intro',
                // 'desc' => '',

                'fields' => [
                ],
            ],
        ],
    ],
];

// var_dump($options); exit;

return $options;
