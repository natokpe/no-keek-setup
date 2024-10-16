<?php
declare(strict_types = 1);

$mail_servers = [];
foreach ((new WP_Query(['post_type' => 'mail_server']))->posts as $ac) {
    $mail_servers[$ac->ID] = $ac->post_title;
}

return [
    'email_account_conf' => [
        'title'        => __('Account Settings', 'natokpe'),
        'object_types' => ['email_account'],
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'closed'       => false,

        'box-fields' => [
            'server' => [
                'name' => __('Mail Server', 'natokpe'),
                'desc' => __('The mail server this email account exist in.', 'natokpe'),
                'type' => 'select',
                'show_option_none' => true,
                'options' => $mail_servers,
                'attributes' => [
                    'required' => 'required',
                ],
            ],

            'username' => [
                'name' => __('Username', 'natokpe'),
                'desc' => __('', 'natokpe'),
                'type' => 'text',
                'attributes' => [
                    'required' => 'required',
                ],
            ],

            'password' => [
                'name' => __('Password', 'natokpe'),
                'desc' => __('', 'natokpe'),
                'type' => 'text',
                'attributes' => [
                    'type' => 'password',
                ],
            ],
            
            'require_auth' => [
                'name' => __('Requires Authentication', 'natokpe'),
                'desc' => __('', 'natokpe'),
                'type' => 'checkbox',
                'default' => false,
            ],

            'sender_address' => [
                'name' => __('Sender/From Address', 'natokpe'),
                'desc' => __('', 'natokpe'),
                'type' => 'text_email',
                'attributes' => [
                    'required' => 'required',
                ],
            ],

            'sender_name' => [
                'name' => __('Sender/From Name', 'natokpe'),
                'desc' => __('', 'natokpe'),
                'type' => 'text',
                'attributes' => [
                ],
            ],
        ],
    ],

    'email_account_on' => [
        'title'        => __('Enable / Disable', 'natokpe'),
        'object_types' => ['email_account'],
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'closed'       => true,

        'box-fields' => [
            'enabled' => [
                'name' => __('Enable', 'natokpe'),
                'desc' => __('Check to allow emails to be sent using this email account.', 'natokpe'),
                'type' => 'checkbox',
                'default' => true,
            ],
        ],
    ],
];
