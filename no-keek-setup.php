<?php
/*
 * Plugin Name:       NO Keek Setup
 * Plugin URI:        https://github.com/natokpe/no-keek-setup/
 * Description:       Provides necessary underlying functionalities.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      8.0
 * Author:            Nat Okpe <me@nat.com.ng>
 * Author URI:        https://nat.com.ng
 * License:           MIT
 * License URI:       
 * Update URI:        https://github.com/natokpe/no-keek-setup/
 * Text Domain:       natokpe
 * Domain Path:       /lang
 */

declare(strict_types = 1);

require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use NatOkpe\Wp\Plugin\KeekSetup\Engine;

if (defined('WP_DEBUG') && WP_DEBUG === true) {
    ini_set("xdebug.var_display_max_depth", "-1");
    ini_set("xdebug.var_display_max_children", "-1");
    ini_set("xdebug.var_display_max_data", "-1");
    ini_set("display_errors", "1");
}

ini_set("upload_max_size", "2048M");
ini_set("post_max_size", "2048M");
ini_set("max_execution_time", "300");

Engine::start(__FILE__);
