<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

declare(strict_types = 1);

namespace NatOkpe\Wp\Plugin\KeekSetup;

use \Mustache_Engine;
use NatOkpe\Wp\Plugin\KeekSetup\Utils\DataList;

use \WP_Post;
use \WP_Error;

class Email
{
    private
    $_mail_server = null;

    private
    $_email_account = null;

    private
    $_message_template = null;

    private
    $_host = null;

    private
    $_port = null;

    private
    $_connection_type = null;

    private
    $_encryption_type = null;

    private
    $_require_auth = null;

    private
    $_username = null;

    private
    $_password = null;

    private
    $_template = [
        'subject'    => null,
        'body_html'  => null,
        'body_plain' => null,
    ];

    private
    $_content_type = null;

    private
    $_message_data = null;

    private
    $_message = null;

    private
    $_sender = [
        'address' => null,
        'name'    => null,
    ];

    private
    $_to = null;

    public
    function __construct(?int $email_account = null, ?int $message_template = null)
    {
        if (isset($email_account)) {
            $this->setAccount($email_account);
        }

        if (isset($message_template)) {
            $this->setTemplate($message_template);
        }
    }

    public
    function getData(): array
    {
        $data = [
            'content_type'    => $this->_content_type,
            'connection_type' => $this->_connection_type,
            'host'            => $this->_host,
            'require_auth'    => $this->_require_auth,
            'port'            => $this->_port,
            'username'        => $this->_username,
            'password'        => $this->_password,
            'encryption_type' => $this->_encryption_type,
            'message'         => $this->_message,
            'sender'          => $this->_sender
        ];

        return $data;
    }

    public
    function setAccount(int $email_account): self
    {
        $ac = get_post($email_account);

        if ($ac instanceOf WP_Post) {
            $svr = get_post_meta($ac->ID, 'server', true);
            $svr = ctype_digit((string) $svr) ? get_post((int) $svr) : null;

            if ($svr instanceOf WP_Post) {
                $this->_email_account = $ac;
                $this->_mail_server   = $svr;

                $this->_connection_type = filter_var(
                    get_post_meta(
                        $this->_mail_server->ID, 'connection_type', true
                    ),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            $ls = DataList::get('email_connection');
                            $rt = array_key_exists($vl, $ls) ? $vl : null;
                            return $rt;
                        },
                    ],
                );

                $this->_host = filter_var(
                    get_post_meta($this->_mail_server->ID, 'host', true),
                    FILTER_VALIDATE_DOMAIN,
                    [
                        'options' => [
                            'default' => null,
                        ],
                        'flags' => FILTER_FLAG_HOSTNAME,
                    ]
                );

                // well-known: 0-1024
                // registered: 1024-49151
                // dynamic and private: 49152-65535
                $this->_port = filter_var(
                    get_post_meta($this->_mail_server->ID, 'port', true),
                    FILTER_VALIDATE_INT,
                    [
                    'options' => [
                        'default' => null,
                        'min_range' => 0,
                        'max_range' => 65535
                    ],
                    'flags' => FILTER_NULL_ON_FAILURE,
                ]);

                $this->_encryption_type = filter_var(
                    get_post_meta($this->_mail_server->ID, 'encryption_type', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            $ls = DataList::get('smtp_encryption');
                            $rt = array_key_exists($vl, $ls) ? $vl : null;
                            return $rt;
                        },
                    ],
                );

                $this->_require_auth = filter_var(
                    get_post_meta($this->_email_account->ID, 'require_auth', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            $rt = is_bool($vl) ? ($vl === true) : null;

                            if (is_string($vl)) {
                                $rt = strtolower($vl) === 'true';
                                $rt = $rt || (strtolower($vl) === 'yes');
                                $rt = $rt || (strtolower($vl) === 'on');
                            }

                            return $rt;
                        },
                    ],
                );

                $this->_username = filter_var(
                    get_post_meta($this->_email_account->ID, 'username', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            return is_string($vl) ? $vl : null;
                        },
                    ],
                );

                $this->_password = filter_var(
                    get_post_meta($this->_email_account->ID, 'password', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            return is_string($vl) ? $vl : null;
                        },
                    ],
                );

                $this->_sender['address'] = filter_var(
                    get_post_meta($this->_email_account->ID, 'sender_address', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            return is_string($vl) ? $vl : null;
                        },
                    ],
                );

                $this->_sender['name'] = filter_var(
                    get_post_meta($this->_email_account->ID, 'sender_name', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            return is_string($vl) ? $vl : null;
                        },
                    ],
                );
            }
        }

        return $this;
    }

    /**
     * TODO: implement array based template
     */
    public
    function setTemplate(int|array $message_template): self
    {
        if (is_int($message_template)) {
            $tpl = get_post($message_template);

            if ($tpl instanceOf WP_Post) {
                $this->_template['subject'] = filter_var(
                    get_post_meta($tpl->ID, 'template_subject', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            $rt = is_string($vl) ? $vl : '';
                            return (! empty($rt)) ? $rt : null;
                        },
                    ],
                );

                $this->_template['body_html'] = filter_var(
                    get_post_meta($tpl->ID, 'template_html', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            $rt = is_string($vl) ? $vl : '';
                            return (! empty($rt)) ? $rt : null;
                        },
                    ],
                );

                $this->_template['body_plain'] = filter_var(
                    get_post_meta($tpl->ID, 'template_plain', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            $rt = is_string($vl) ? $vl : '';
                            return (! empty($rt)) ? $rt : null;
                        },
                    ],
                );

                $this->_content_type = filter_var(
                    get_post_meta($tpl->ID, 'content_type', true),
                    FILTER_CALLBACK,
                    [
                        'options' => function ($vl) {
                            $ls = DataList::get('content_type');
                            $rt = array_key_exists($vl, $ls) ? $vl : null;
                            return $rt;
                        },
                    ],
                );
            }
        }

        if (is_string($message_template)) {
        }

        return $this;
    }

    public
    function prepare(array $message_data, ?array $sender = null): self
    {
        $this->_message_data = $message_data;

        $templateEngine  = new Mustache_Engine();

        $this->_message = [
            'subject' => $templateEngine->render(
                $this->_template['subject'] ?? '',
                $this->_message_data
            ),

            'body_html' => $templateEngine->render(
                $this->_template['body_html'] ?? '',
                $this->_message_data
            ),

            'body_plain' => $templateEngine->render(
                $this->_template['body_plain'] ?? '',
                $this->_message_data
            ),
        ];

        if (is_array($sender)) {
            $this->_sender['address'] = is_string($sender['address'] ?? null) ? $sender['address'] : $this->_sender['address'];
            $this->_sender['name'] = is_string($sender['name'] ?? null) ? $sender['name'] : $this->_sender['name'];
        }

        return $this;
    }

    public
    function send(string|array $to): bool
    {
        global $phpmailer;

        $this->_to = $to;

        $att = [
            'to' => $to,
        ];

        if ($this->_email_account instanceOf WP_Post) {
            $account_on = filter_var(
                get_post_meta($this->_email_account->ID, 'enabled', true),
                FILTER_CALLBACK,
                [
                    'options' => function ($vl) {
                        $rt = is_bool($vl) ? ($vl === true) : false;

                        if (is_string($vl)) {
                            $rt = strtolower($vl) === 'true';
                            $rt = $rt || (strtolower($vl) === 'yes');
                            $rt = $rt || (strtolower($vl) === 'on');
                        }

                        return $rt;
                    },
                ],
            );

            if (! $account_on) {
                do_action(
                    'wp_mail_failed',
                    new WP_Error(
                        'wp_mail_failed',
                        'Email sending disabled on email account',
                        $att
                    )
                );

                return false;
            }
        } else {
            do_action(
                'wp_mail_failed',
                new WP_Error(
                    'wp_mail_failed',
                    'No email account selected',
                    $att
                )
            );

            return false;
        }

        if ($this->_mail_server instanceOf WP_Post) {
            $server_on = filter_var(
                get_post_meta($this->_mail_server->ID, 'enabled', true),
                FILTER_CALLBACK,
                [
                    'options' => function ($vl) {
                        $rt = is_bool($vl) ? ($vl === true) : false;

                        if (is_string($vl)) {
                            $rt = strtolower($vl) === 'true';
                            $rt = $rt || (strtolower($vl) === 'yes');
                            $rt = $rt || (strtolower($vl) === 'on');
                        }

                        return $rt;
                    },
                ],
            );

            if (! $server_on) {
                do_action(
                    'wp_mail_failed',
                    new WP_Error(
                        'wp_mail_failed',
                        'Email sending disabled on mail server',
                        $att
                    )
                );

                return false;
            }
        } else {
            do_action(
                'wp_mail_failed',
                new WP_Error(
                    'wp_mail_failed',
                    'Mail server not set',
                    $att
                )
            );

            return false;
        }

        $setup = $this->getData();

        add_filter('phpmailer_init', function($phpmailer) use ($setup) {
            if ($setup['connection_type'] === 'smtp') {
                $phpmailer->isSMTP();
            }

            $phpmailer->Host       = $setup['host'];
            $phpmailer->SMTPAuth   = $setup['require_auth'];
            $phpmailer->Port       = $setup['port'];
            $phpmailer->Username   = $setup['username'];
            $phpmailer->Password   = $setup['password'];
            $phpmailer->SMTPSecure = $setup['encryption_type'];

            $phpmailer->setFrom(
                $setup['sender']['address'] ?? '',
                $setup['sender']['name'] ?? ''
            );

            if ($setup['content_type'] === 'text/html') {
                $phpmailer->isHTML(true);

                $phpmailer->Body    = $setup['message']['body_html'];
                $phpmailer->AltBody = $setup['message']['body_plain'];
            } else {
                $phpmailer->Body = $setup['message']['body_plain'];
            }

            $phpmailer->Subject = $setup['message']['subject'];
        });

        $send = wp_mail(
            $to,
            $setup['message']['subject'] ?? '',
            $setup['message']['body_plain'] ?? ''
        );

        if (isset($phpmailer)) {
            unset($phpmailer);
        }

        return $send;
    }

    /**
     * TODO: implement
     */
    public static
    function createFromLog(int $email_log): self
    {
        return new self();
    }
}
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
