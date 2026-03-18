<?php
// config/config.php
define('SITE_NAME',  'EMOVTT Santa Rosa');
define('SITE_URL',   'https://emovtt-sr.gob.ec');
define('SITE_EMAIL', 'info@emovtt-sr.gob.ec');
define('SITE_TEL',   '(07) 2123-456');
define('BASE_PATH',  __DIR__ . '/..');
define('UPLOADS_DIR', BASE_PATH . '/uploads');

// BASE_URL dinamico: funciona en localhost, subcarpeta y produccion
// localhost/emovtt     -> http://localhost/emovtt
// emovtt-sr.gob.ec    -> https://emovtt-sr.gob.ec
$_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_script   = dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php');
$_base     = rtrim($_script === DIRECTORY_SEPARATOR ? '' : $_script, '/');
define('BASE_URL', $_protocol . '://' . $_host . $_base);
unset($_protocol, $_host, $_script, $_base);
