<?php
declare(strict_types = 1);

use NatOkpe\Wp\Plugin\KeekSetup\Utils\DataList;

// email template charset
return [
    'email_template_conf' => [
        'title'         => __('Template Settings', 'natokpe'),
        'object_types'  => ['email_template'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
        'closed'        => false,

        'box-fields' => [
            'content_type' => [
                'name' => __('Content Type', 'natokpe'),
                'desc' => __('', 'natokpe'),
                'type' => 'select',
                'options' => DataList::get('content_type'),
            ],

            'desc' => [
                'name' => __('Description', 'natokpe'),
                'desc' => __('Briefly describe the template.', 'natokpe'),
                'type' => 'textarea',
                'attributes' => [
                    'style' => 'width: 100%;',
                    'rows' => 2,
                ],
            ],
        ],
    ],

    'email_template_body' => [
        'title'         => __('Nessage Template', 'natokpe'),
        'object_types'  => ['email_template'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
        'closed'        => false,

        'box-fields' => [
            'template_subject' => [
                'name'    => __('Subject', 'natokpe'),
                'desc'    => __('Can include variables', 'natokpe'),
                'type'    => 'textarea',
                'sanitization_cb' => false,
                'attributes' => [
                    'style' => 'width: 100%;',
                    'rows' => 2,
                ],
            ],
            
            'template_plain' => [
                'name'    => __('Plain', 'natokpe'),
                'desc'    => __("Used as Alt body if Content Type is set to 'HTML'", 'natokpe'),
                'type'    => 'textarea',
                'sanitization_cb' => false,
                'attributes' => [
                    'style' => 'width: 100%;'
                ],
            ],
            
            'template_html' => [
                'name'    => __('HTML', 'natokpe'),
                'desc'    => __("Ignored if Content Type is set to 'Plain Text'", 'natokpe'),
                'type'    => 'textarea',
                'sanitization_cb' => false,
                'attributes' => [
                    'style' => 'width: 100%;'
                ],
            ],
        ],
    ],

    'email_template_var' => [
        'title'         => __('Variable Reference', 'natokpe'),
        'object_types'  => ['email_template'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
        'closed'        => true,

        'box-fields' => [
            'template_plain' => [
                'desc'    => __('<h3>General</h3>', 'natokpe'),
                'type'    => 'title',
                'attributes' => [
                    'style' => 'width: 100%;'
                ],
            ],
        ],
    ],
];
