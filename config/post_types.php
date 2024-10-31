<?php
declare(strict_types = 1);

use NatOkpe\Wp\Plugin\KeekSetup\Engine;

$pt_dir = __DIR__ . DIRECTORY_SEPARATOR . 'post-types' . DIRECTORY_SEPARATOR;
$pt     = [];

$pt[] = require($pt_dir . 'course.php');
$pt[] = require($pt_dir . 'application.php');
$pt[] = require($pt_dir . 'faq.php');
$pt[] = require($pt_dir . 'email_account.php');
$pt[] = require($pt_dir . 'message_template.php');
$pt[] = require($pt_dir . 'mail_server.php');

$pt = array_merge(...$pt);

return $pt;
