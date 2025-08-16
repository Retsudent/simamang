<?php
/**
 * Router for PHP Built-in Server
 * This file handles routing for CodeIgniter when using PHP's built-in server
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a CodeIgniter
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Everything else is handled by CodeIgniter
require_once __DIR__ . '/index.php';
