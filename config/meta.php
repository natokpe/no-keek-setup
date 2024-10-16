<?php
declare(strict_types = 1);

use NatOkpe\Wp\Plugin\KeekSetup\Engine;

$meta_dir = __DIR__ . DIRECTORY_SEPARATOR . 'meta' . DIRECTORY_SEPARATOR;
$meta     = [];

$meta[] = require($meta_dir . 'meta-mail_server.php');
$meta[] = require($meta_dir . 'meta-email_account.php');
$meta[] = require($meta_dir . 'meta-email_template.php');

$meta = array_merge(...$meta);

return $meta;
