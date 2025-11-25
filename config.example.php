<?php
/**
 * Configuration file for SmartBudget - EXAMPLE FILE
 * Defines base URL and paths
 * 
 * INSTRUCTIONS:
 * This file should work automatically for most setups.
 * If you need custom configuration, copy this to config.local.php
 * and modify the values there.
 */

// Determine the base URL automatically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// Auto-detect base path from the current script location
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = $scriptPath;

// If we're in the public directory, go up one level for the base path
if (basename($scriptPath) === 'public') {
    $basePath = $scriptPath . '/';
} else {
    // Assume we're in the root, add /public/
    $basePath = rtrim($scriptPath, '/') . '/public/';
}

// Allow override via config.local.php
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
} else {
    define('BASE_PATH', $basePath);
    define('BASE_URL', $protocol . '://' . $host . BASE_PATH);
}

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
