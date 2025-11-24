<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
requireAuth();
$user = getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $budget_id = $_POST['budget_id'] ?? null;
    $type = $_POST['type'];
    $categorie = $_POST['categorie'] ?? '';
    $montant = floatval($_POST['montant']);
    $date_tx = $_POST['date_tx'];
    
    // If no budget_id provided or empty, create a default budget for the user
    if (empty($budget_id)) {
        // Check if user already has a budget
        $stmt = $pdo->prepare('SELECT b.id_budget FROM budgets b JOIN users u ON u.id_budget = b.id_budget WHERE u.id_user = ? LIMIT 1');
        $stmt->execute([$user['id_user']]);
        $existing_budget = $stmt->fetch();
        
        if ($existing_budget) {
            $budget_id = $existing_budget['id_budget'];
        } else {
            // Create a default budget for the user
            $stmt = $pdo->prepare('INSERT INTO budgets (titre, objectif, periode_debut, periode_fin) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                'Mon Budget',
                10000, // Default objective
                date('Y-m-01'), // First day of current month
                date('Y-m-t')   // Last day of current month
            ]);
            $budget_id = $pdo->lastInsertId();
            
            // Link budget to user
            $stmt = $pdo->prepare('UPDATE users SET id_budget = ? WHERE id_user = ?');
            $stmt->execute([$budget_id, $user['id_user']]);
        }
    }
    
    // Now insert the transaction
    $stmt = $pdo->prepare('INSERT INTO transactions (budget_id, user_id, type, categorie, montant, date_tx) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$budget_id, $user['id_user'], $type, $categorie, $montant, $date_tx]);
    
    // Update budget totals and epargne
    if ($type === 'depense') {
        $pdo->prepare('UPDATE budgets SET total_depenses = total_depenses + ?, epargne = total_revenus - (total_depenses + ?) WHERE id_budget = ?')->execute([$montant, $montant, $budget_id]);
    } else {
        $pdo->prepare('UPDATE budgets SET total_revenus = total_revenus + ?, epargne = (total_revenus + ?) - total_depenses WHERE id_budget = ?')->execute([$montant, $montant, $budget_id]);
    }
    
    redirect('dashboard.php');
}
?>