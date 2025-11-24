<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

// Redirect to dashboard if already logged in
if (isLoggedIn()) {
    redirect('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget - Gérez votre budget intelligemment</title>
    <meta name="description" content="SmartBudget vous aide à gérer vos finances personnelles avec des outils intelligents et une communauté active">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo url('css/style.css'); ?>">
    <style>
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--color-text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }
        
        .feature-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-xl);
            padding: 2rem;
            text-align: left;
            transition: all var(--transition-base);
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl), var(--shadow-glow);
            border-color: rgba(99, 102, 241, 0.4);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--color-text-primary);
        }
        
        .feature-description {
            color: var(--color-text-secondary);
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-content fade-in">
            <h1 class="hero-title">
                Gérez votre budget intelligemment
            </h1>
            <p class="hero-subtitle">
                SmartBudget vous aide à prendre le contrôle de vos finances avec des outils puissants, 
                des visualisations claires et une communauté active pour vous soutenir.
            </p>
            
            <div class="hero-buttons">
                <a href="<?php echo url('register.php'); ?>" class="btn btn-primary">
                    Commencer gratuitement
                </a>
                <a href="<?php echo url('login.php'); ?>" class="btn btn-secondary">
                    Se connecter
                </a>
            </div>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Suivi en temps réel</h3>
                    <p class="feature-description">
                        Visualisez vos revenus et dépenses avec des graphiques interactifs et des statistiques détaillées.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3 class="feature-title">Gestion de budget</h3>
                    <p class="feature-description">
                        Créez des budgets personnalisés, définissez des objectifs d'épargne et suivez vos progrès.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3 class="feature-title">Communauté active</h3>
                    <p class="feature-description">
                        Partagez vos objectifs, posez des questions et apprenez des expériences des autres membres.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
