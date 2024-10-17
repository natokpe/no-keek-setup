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
            $svr = get_post_meta($ac->ID, 'server', null)[0] ?? null;
            $svr = ctype_digit((string) $svr) ? get_post((int) $svr) : null;

            if ($svr instanceOf WP_Post) {
                $this->_email_account = $ac;
                $this->_mail_server   = $svr;

                $this->_connection_type = get_post_meta($this->_mail_server->ID, 'connection_type', null)[0] ?? null;
                $this->_connection_type = array_key_exists($this->_connection_type, DataList::get('email_connection')) ? $this->_connection_type : null;

                $this->_host = get_post_meta($this->_mail_server->ID, 'host', null)[0] ?? null;

                $this->_port = get_post_meta($this->_mail_server->ID, 'port', null)[0] ?? null;
                $this->_port = ctype_digit((string) $this->_port) ? (int) $this->_port : null;

                $this->_encryption_type = get_post_meta($this->_mail_server->ID, 'encryption_type', null)[0] ?? null;
                $this->_encryption_type = array_key_exists($this->_encryption_type, DataList::get('smtp_encryption')) ? $this->_encryption_type : null;

                $this->_require_auth = get_post_meta($this->_email_account->ID, 'require_auth', false)[0] ?? null;
                $this->_require_auth = is_string($this->_require_auth) ? (strtolower($this->_require_auth) === 'on') : $this->_require_auth === true;

                $this->_username = get_post_meta($this->_email_account->ID, 'username', null)[0] ?? null;
                $this->_password = get_post_meta($this->_email_account->ID, 'password', null)[0] ?? null;

                $this->_sender['address'] = get_post_meta($this->_email_account->ID, 'sender_address', null)[0] ?? null;
                $this->_sender['name']    = get_post_meta($this->_email_account->ID, 'sender_name', null)[0] ?? null;
            }
        }

        return $this;
    }

    public
    function setTemplate(int $message_template): self
    {
        $tpl = get_post($message_template);

        if ($tpl instanceOf WP_Post) {
            $this->_message_template = $tpl;

            $this->_template['subject']    = get_post_meta($tpl->ID, 'template_subject', '')[0] ?? null;
            $this->_template['body_html']  = get_post_meta($tpl->ID, 'template_html', '')[0] ?? null;
            $this->_template['body_plain'] = get_post_meta($tpl->ID, 'template_plain', '')[0] ?? null;
            $this->_content_type           = get_post_meta($tpl->ID, 'content_type', false)[0] ?? null;

            $this->_content_type = array_key_exists($this->_content_type, DataList::get('content_type')) ? $this->_content_type : null;
        }

        return $this;
    }

    public
    function prepare(array $message_data, ?array $sender = null): self
    {
        $this->_message_data = $message_data;

        $templateEngine  = new Mustache_Engine();

        $this->_message = [
            'subject'    => $templateEngine->render($this->_template['subject'] ?? '', $this->_message_data),
            'body_html'  => $templateEngine->render($this->_template['body_html'] ?? '', $this->_message_data),
            'body_plain' => $templateEngine->render($this->_template['body_plain'] ?? '', $this->_message_data),
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
        $this->_to = $to;

        $att = [
            'to' => $to,
        ];

        if (! ($this->_mail_server instanceOf WP_Post)) {
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

        if (! ($this->_email_account instanceOf WP_Post)) {
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

        // if ($this->_mail_server)
        $server_on = get_post_meta($this->_mail_server->ID, 'enabled', null)[0] ?? null;

        if (! (is_string($server_on) ? (strtolower($server_on) === 'on') : $server_on === true)) {
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

        $sccount_on  = get_post_meta($this->_mail_server->ID, 'enabled', null)[0] ?? null;

        if (! (is_string($sccount_on) ? (strtolower($sccount_on) === 'on') : $sccount_on === true)) {
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

            $phpmailer->setFrom($setup['sender']['address'] ?? '', $setup['sender']['name'] ?? '');

            if ($setup['content_type'] === 'text/html') {
                $phpmailer->isHTML(true);

                $phpmailer->Body    = $setup['message']['body_html'];
                $phpmailer->AltBody = $setup['message']['body_plain'];
            } else {
                $phpmailer->Body = $setup['message']['body_plain'];
            }

            $phpmailer->Subject = $setup['message']['subject'];
        });

        $send = wp_mail($to, $setup['message']['subject'] ?? '', $setup['message']['body_plain'] ?? '');

        return $send;
    }
}
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
