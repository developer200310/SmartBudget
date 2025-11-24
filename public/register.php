<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($nom) || empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas';
    } elseif (registerUser($nom, $email, $password)) {
        $success = 'Inscription réussie ! Redirection...';
        header('Refresh: 2; URL=' . url('login.php'));
    } else {
        $error = 'Erreur lors de l\'inscription. Cet email est peut-être déjà utilisé.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - SmartBudget</title>
    <meta name="description" content="Créez votre compte SmartBudget">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo url('css/style.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card fade-in">
            <h1 class="auth-title">Créer un compte</h1>
            
            <?php if (!empty($error)): ?>
                <div class="form-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="form-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom" class="form-label">Nom complet</label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        class="form-input" 
                        placeholder="Jean Dupont"
                        required
                        value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="votre@email.com"
                        required
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="••••••••"
                        required
                        minlength="6"
                    >
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-input" 
                        placeholder="••••••••"
                        required
                        minlength="6"
                    >
                </div>
                
                <button type="submit" class="btn btn-primary btn-full">
                    S'inscrire
                </button>
            </form>
            
            <div class="auth-footer">
                Déjà un compte ? 
                <a href="<?php echo url('login.php'); ?>">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>