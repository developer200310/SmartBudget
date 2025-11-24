<?php
require_once __DIR__ . '/../db.php';

function registerUser($nom, $email, $password) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (nom, email, password_hash) VALUES (?, ?, ?)');
    return $stmt->execute([$nom, $email, $hash]);
}

function loginUser($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        // set session
        $_SESSION['user'] = [
            'id_user' => $user['id_user'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        return true;
    }
    return false;
}

function requireAuth() {
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }
}
?>