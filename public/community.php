<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
requireAuth();
$user = getCurrentUser();

// Handle new post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu'])) {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu']);
    $type = $_POST['type'] ?? 'partage';
    
    if (!empty($contenu)) {
        $stmt = $pdo->prepare('INSERT INTO posts (author_id, titre, contenu, type) VALUES (?, ?, ?, ?)');
        $stmt->execute([$user['id_user'], $titre ?: null, $contenu, $type]);
        redirect('community.php');
    }
}

// Fetch posts
$stmt = $pdo->query('SELECT p.*, u.nom as author FROM posts p JOIN users u ON u.id_user = p.author_id ORDER BY p.created_at DESC LIMIT 50');
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauté - SmartBudget</title>
    <meta name="description" content="Communauté SmartBudget">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo url('css/style.css'); ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container navbar-content">
            <div class="navbar-brand">SmartBudget</div>
            <div class="navbar-links">
                <a href="<?php echo url('dashboard.php'); ?>">Dashboard</a>
                <a href="<?php echo url('community.php'); ?>">Communauté</a>
                <a href="<?php echo url('logout.php'); ?>">Déconnexion</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
        <h1 class="fade-in">Communauté 💬</h1>
        <p style="color: var(--color-text-secondary); margin-bottom: 2rem;">
            Partagez vos objectifs, posez des questions et apprenez des autres membres.
        </p>

        <!-- New Post Form -->
        <section class="glass-card fade-in" style="margin-bottom: 2rem;">
            <h2 style="margin-bottom: 1.5rem;">Créer une publication</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="titre" class="form-label">Titre (optionnel)</label>
                    <input 
                        type="text" 
                        id="titre" 
                        name="titre" 
                        class="form-input" 
                        placeholder="Donnez un titre à votre publication"
                    >
                </div>
                
                <div class="form-group">
                    <label for="contenu" class="form-label">Contenu</label>
                    <textarea 
                        id="contenu" 
                        name="contenu" 
                        required 
                        class="form-textarea" 
                        placeholder="Partagez votre objectif, réussite ou question..."
                    ></textarea>
                </div>
                
                <div class="form-group">
                    <label for="type" class="form-label">Type de publication</label>
                    <select name="type" id="type" class="form-select">
                        <option value="partage">Partage</option>
                        <option value="objectif">Objectif</option>
                        <option value="question">Question</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    Publier
                </button>
            </form>
        </section>

        <!-- Posts List -->
        <section class="fade-in">
            <h2 style="margin-bottom: 1.5rem;">Publications récentes</h2>
            
            <?php if (empty($posts)): ?>
                <div class="glass-card text-center">
                    <p style="color: var(--color-text-secondary);">
                        Aucune publication pour le moment. Soyez le premier à partager !
                    </p>
                </div>
            <?php else: ?>
                <?php foreach($posts as $p): ?>
                    <article class="post-card">
                        <div class="post-header">
                            <div>
                                <strong class="post-author"><?php echo htmlspecialchars($p['author']); ?></strong>
                                <span style="margin-left: 0.5rem; padding: 0.25rem 0.75rem; background: var(--glass-bg); border-radius: var(--radius-full); font-size: 0.75rem; color: var(--color-text-muted);">
                                    <?php echo htmlspecialchars($p['type']); ?>
                                </span>
                            </div>
                            <small class="post-date"><?php echo htmlspecialchars($p['created_at']); ?></small>
                        </div>
                        
                        <?php if($p['titre']): ?>
                            <h3 class="post-title"><?php echo htmlspecialchars($p['titre']); ?></h3>
                        <?php endif; ?>
                        
                        <p class="post-content"><?php echo nl2br(htmlspecialchars($p['contenu'])); ?></p>
                        
                        <div class="post-actions">
                            <form style="display:inline;" method="POST" action="<?php echo url('like.php'); ?>">
                                <input type="hidden" name="id_post" value="<?php echo $p['id_post']; ?>">
                                <button type="submit" class="post-action-btn">
                                    ❤️ <?php echo $p['likes_count']; ?>
                                </button>
                            </form>
                            <a href="<?php echo url('post.php?id=' . $p['id_post']); ?>" class="post-action-btn">
                                💬 Commentaires
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
