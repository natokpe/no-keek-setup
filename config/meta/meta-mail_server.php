<?php
declare(strict_types = 1);

use NatOkpe\Wp\Plugin\KeekSetup\Utils\DataList;

/*
quota_usage counts the number of emails sent
quota_limit maximum number of emails allowed
*/

return [
    'mail_server_conf' => [
        'title'        => __('Server Configuration', 'natokpe'),
        'object_types' => ['mail_server'],
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'closed'       => false,

        'box-fields' => [
            'connection_type' => [
                'name' => __('Connection Type', 'natokpe'),
                'desc' => __('', 'natokpe'),
                'type' => 'select',
                'options' => DataList::get('email_connection'),
                'attributes' => [
                    'required' => 'required',
                ],
            ],

            'host' => [
                'name' => __('Hostname', 'natokpe'),
                'desc' => __('Set the hostname of the mail server. E.g mail.example.com', 'natokpe'),
                'type' => 'text',
                'attributes' => [
                    'required' => 'required',
                ],
            ],

            'port' => [
                'name' => __('Port', 'natokpe'),
                'desc' => __('Set the port number', 'natokpe'),
                'type' => 'text',
                'attributes' => [
                    'type' => 'number',
                    'min' => 1,
                    'step' => 1,
                    'required' => 'required',
                ],
            ],

            'encryption_type' => [
                'name' => __('Encryption Type', 'natokpe'),
                'desc' => __('The encryption mechanism used by the mail server', 'natokpe'),
                'type' => 'select',
                'options' => DataList::get('smtp_encryption'),
            ],

        ],
    ],

    'mail_server_srate' => [
        'title'        => __('Email Sending Frequency', 'natokpe'),
        'object_types' => ['mail_server'],
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'closed'       => true,

        'box-fields' => [
            'limit_amount' => [
                'name' => __('Amount', 'natokpe'),
                'desc' => __('Number of emails limit', 'natokpe'),
                'type' => 'text',
                'default' => '1',
                'attributes' => [
                    'type' => 'number',
                    'min' => 1,
                    'step' => 1,
                ],
            ],

            'limit_interval' => [
                'name' => __('Interval', 'natokpe'),
                'desc' => __('In seconds', 'natokpe'),
                'type' => 'text',
                'default' => '5',
                'attributes' => [
                    'type' => 'number',
                    'min' => 1,
                ],
            ],
        ],
    ],

    'mail_server_usage' => [
        'title'        => __('Activation Settings', 'natokpe'),
        'object_types' => ['mail_server'],
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'closed'       => true,

        'box-fields' => [
            'default_act_mode' => [
                'name' => __('Default Activation Mode', 'natokpe'),
                'desc' => __('Number of emails limit', 'natokpe'),
                'type' => 'select',
                'default' => 'worker',
                'options' => [
                    'instant' => 'Instant',
                    'worker' => 'Worker Managed',
                ],
            ],
        ],
    ],

    'mail_server_on' => [
        'title'        => __('Enable / Disable', 'natokpe'),
        'object_types' => ['mail_server'],
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'closed'       => true,

        'box-fields' => [
            'enabled' => [
                'name' => __('Enable', 'natokpe'),
                'desc' => __('Check to allow emails through this server to be sent', 'natokpe'),
                'type' => 'checkbox',
                'default' => true,
            ],
        ],
    ],
];
