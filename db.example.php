<?php
/**
 * Database Configuration and Connection - EXAMPLE FILE
 * SmartBudget Application
 * 
 * INSTRUCTIONS:
 * 1. Copy this file to db.php
 * 2. Update the database credentials below with your own
 * 3. Make sure db.php is in .gitignore (it should be by default)
 */

// Database configuration - UPDATE THESE VALUES
define('DB_HOST', 'localhost');
define('DB_NAME', 'smartbudget');
define('DB_USER', 'root');
define('DB_PASS', ''); // Add your MySQL password here if needed
define('DB_CHARSET', 'utf8mb4');

// PDO options for better error handling and security
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create PDO connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Une erreur de connexion à la base de données s'est produite. Veuillez réessayer plus tard.");
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
