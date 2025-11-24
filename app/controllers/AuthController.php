<?php
/**
 * Authentication Controller
 * Handles user registration, login, and session management
 */

require_once __DIR__ . '/../../db.php';

/**
 * Register a new user
 * @param string $nom User's name
 * @param string $email User's email
 * @param string $password User's password
 * @return bool Success status
 */
function registerUser($nom, $email, $password) {
    global $pdo;
    
    try {
        // Hash the password
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $stmt = $pdo->prepare('INSERT INTO users (nom, email, password_hash) VALUES (?, ?, ?)');
        return $stmt->execute([$nom, $email, $hash]);
    } catch (PDOException $e) {
        // Log error
        error_log("Registration Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Login user with email and password
 * @param string $email User's email
 * @param string $password User's password
 * @return bool Success status
 */
function loginUser($email, $password) {
    global $pdo;
    
    try {
        // Fetch user by email
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        // Verify password
        if ($user && password_verify($password, $user['password_hash'])) {
            // Set session data
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'nom' => $user['nom'],
                'email' => $user['email'],
                'role' => $user['role'],
                'avatar_url' => $user['avatar_url']
            ];
            return true;
        }
        
        return false;
    } catch (PDOException $e) {
        // Log error
        error_log("Login Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Require authentication - redirect to login if not authenticated
 */
function requireAuth() {
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user']);
}

/**
 * Get current logged in user
 * @return array|null User data or null
 */
function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

/**
 * Logout current user
 */
function logoutUser() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy the session
    session_destroy();
}

/**
 * Check if user has specific role
 * @param string $role Role to check
 * @return bool
 */
function hasRole($role) {
    $user = getCurrentUser();
    return $user && $user['role'] === $role;
}
?>
