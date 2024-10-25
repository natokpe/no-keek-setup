<?php
declare(strict_types = 1);

use NatOkpe\Wp\Plugin\KeekSetup\Engine;

/* Config: Engine */
return [
    'show_admin_bar' => false,
    
    'admin_footer_text' => sprintf(
        'Need help? &mdash; '
        . '<a %4$s href="mailto:%1$s">Email</a> | '
        . '<a %4$s href="tel:%2$s">Call</a> | '
        . '<a %4$s href="https://api.whatsapp.com/send?phone=%3$s">WhatsApp</a>',
        esc_attr('support@nat.com.ng'),
        esc_attr('+234-703-929-0234'),
        esc_attr('2347039290234'),
        'target="_blank" rel="noopener noreferrer"'
    ),

    'update_footer' => sprintf(
        '<a %s href="%s">Create a website</a>',
        'target="_blank" rel="noopener noreferrer"',
        esc_attr('https://nat.com.ng')
    ),

    'image_sizes' => [
        // 'image-32' => [
        //     'width' => 32,
        //     'height' => 32,
        //     'crop' => true,
        // ],
    ],

    'upload_mimes' => function ($mimes) {
        return array_merge($mimes, [
            // Images
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'gif' => 'image/gif',
            'webp' => 'image/webp',

            // Docs
            'pdf' => 'application/pdf',
        ]);
    },

    'query_vars' => [
        'action',
        'nnc', // nonce
        'pg',
    ],
];
