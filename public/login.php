<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } elseif (loginUser($email, $password)) {
        redirect('dashboard.php');
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SmartBudget</title>
    <meta name="description" content="Connectez-vous à votre compte SmartBudget">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo url('css/style.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card fade-in">
            <h1 class="auth-title">Connexion</h1>
            
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
                    >
                </div>
                
                <button type="submit" class="btn btn-primary btn-full">
                    Se connecter
                </button>
            </form>
            
            <div class="auth-footer">
                Pas encore de compte ? 
                <a href="<?php echo url('register.php'); ?>">Créer un compte</a>
            </div>
        </div>
    </div>
</body>
</html>