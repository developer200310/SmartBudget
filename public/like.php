<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
requireAuth();
$user = getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['id_post']);
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT IGNORE INTO likes (post_id, user_id) VALUES (?, ?)');
        $stmt->execute([$post_id, $user['id_user']]);
        $pdo->prepare('UPDATE posts SET likes_count = (SELECT COUNT(*) FROM likes WHERE post_id = ?) WHERE id_post = ?')->execute([$post_id, $post_id]);
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

redirect('community.php');
?>