<?php
// Config App constants
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $baseUrl = rtrim($protocol . "://" . $host . $scriptDir, '/');
    define('BASE_URL', $baseUrl);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
