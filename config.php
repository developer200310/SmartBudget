<?php
/**
 * Configuration file for SmartBudget
 * Defines base URL and paths
 */

// Determine the base URL automatically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// Base path - adjust this if your app is in a subdirectory
// For XAMPP: /Relationnel/public/
// For root: /
define('BASE_PATH', '/Relationnel/public/');
define('BASE_URL', $protocol . '://' . $host . BASE_PATH);

/**
 * Helper function to generate URLs
 * @param string $path Path relative to public directory
 * @return string Full URL
 */
function url($path = '') {
    $path = ltrim($path, '/');
    return BASE_PATH . $path;
}

/**
 * Helper function to redirect
 * @param string $path Path to redirect to
 */
function redirect($path) {
    header('Location: ' . url($path));
    exit;
}
?>
