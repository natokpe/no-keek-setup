<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

declare(strict_types = 1);

namespace NatOkpe\Wp\Plugin\KeekSetup\Utils;

class DataList
{
    public static
    function get(string $list, bool $as_object = false): array|object
    {
        $result = [];

        switch ($list) {
            case 'email_connection':
                $result = [
                    'smtp' => 'SMTP',
                ];
                break;

            case 'smtp_encryption':
                $result = [
                    'tls' => 'STARTTLS',
                    'ssl' => 'SMTPS',
                ];
                break;

            case 'content_type':
                $result = [
                    'text/plain' => 'Plain Text',
                    'text/html'  => 'HTML',
                ];
                break;

            default:
                break;
        }

        return $result;
    }
}
