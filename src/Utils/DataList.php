<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

declare(strict_types = 1);

namespace NatOkpe\Wp\Plugin\KeekSetup\Utils;

class DataList
{
    public static
    function get(string $list, bool $as_object = false): array|object
    {
        $lists = [
            'email_connection' => [
                'smtp' => 'SMTP',
            ],

            'smtp_encryption' => [
                'tls' => 'STARTTLS',
                'ssl' => 'SMTPS',
            ],

            'content_type' => [
                'text/plain' => 'Plain Text',
                'text/html'  => 'HTML',
            ],

            'gender' => [
                'male'       => 'Male',
                'female'     => 'Female',
                'non-binary' => 'Non-Binary',
            ],

            'marital_status' => [
                'single'   => 'Single',
                'married'  => 'Married',
                'divorced' => 'Divorced',
            ],
        ];

        return $as_object
        ? ((object) ($lists[$list] ?? []))
        : $lists[$list] ?? [];
    }
}
