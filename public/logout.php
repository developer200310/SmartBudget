<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

// Logout the user
logoutUser();

// Redirect to login page
redirect('login.php');
?>