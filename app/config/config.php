<?php

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}

if (!defined('APPPATH')) {
    define('APPPATH', ROOTPATH . '/app');
}

if (!defined('PUBLICPATH')) {
    define('PUBLICPATH', ROOTPATH . '/public');
}

if (!defined('APPROOT')) {
    define('APPROOT', APPPATH);
}

$hostActual = $_SERVER['HTTP_HOST'] ?? 'localhost';

$esLocal = (
    strpos($hostActual, 'localhost') !== false ||
    strpos($hostActual, '127.0.0.1') !== false ||
    strpos($hostActual, '::1') !== false
);

if ($esLocal) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_NAME', 'censo_tlalpan');
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'admin_encuesta');
    define('DB_PASS', 'root');
    define('DB_NAME', 'censo_tlalpan');
}

define('DB_CHARSET', 'utf8mb4');

if ($esLocal) {
    define('URLROOT', 'http://localhost:8000');
    define('APP_ENV', 'local');
} else {
    define('URLROOT', 'https://tierraconcorazon.tlalpan.gob.mx');
    define('APP_ENV', 'production');
}

define('SITENAME', 'Ventanilla Única de Trámites');

if (APP_ENV === 'local') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
}