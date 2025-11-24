<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
requireAuth();
$user = getCurrentUser();

// Fetch user's budget (if any)
$stmt = $pdo->prepare('SELECT b.* FROM budgets b JOIN users u ON u.id_budget = b.id_budget WHERE u.id_user = ? LIMIT 1');
$stmt->execute([$user['id_user']]);
$budget = $stmt->fetch();

// Fetch transactions for chart
$transactions = [];
if ($budget) {
    $stmt = $pdo->prepare('SELECT date_tx, type, montant, categorie FROM transactions WHERE budget_id = ? ORDER BY date_tx ASC');
    $stmt->execute([$budget['id_budget']]);
    $transactions = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartBudget</title>
    <meta name="description" content="Tableau de bord SmartBudget">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo url('css/style.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <h1 class="fade-in">Bonjour, <?php echo htmlspecialchars($user['nom']); ?> 👋</h1>

        <!-- Stats Cards -->
        <section class="grid grid-3 fade-in" style="margin-bottom: 2rem;">
            <div class="stat-card">
                <div class="stat-label">Revenus totaux</div>
                <div class="stat-value">
                    <?php echo $budget ? number_format($budget['total_revenus'], 2) . ' MAD' : '0.00 MAD'; ?>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-label">Dépenses totales</div>
                <div class="stat-value">
                    <?php echo $budget ? number_format($budget['total_depenses'], 2) . ' MAD' : '0.00 MAD'; ?>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-label">Épargne</div>
                <div class="stat-value">
                    <?php echo $budget ? number_format($budget['epargne'], 2) . ' MAD' : '0.00 MAD'; ?>
                </div>
            </div>
        </section>

        <!-- Chart Section -->
        <section class="glass-card fade-in" style="margin-bottom: 2rem;">
            <h2 style="margin-bottom: 1.5rem;">Graphique des transactions</h2>
            <canvas id="txChart" height="80"></canvas>
        </section>

        <!-- Transaction Form & Summary -->
        <section class="grid grid-2 fade-in">
            <div class="glass-card">
                <h3 style="margin-bottom: 1.5rem;">Ajouter une transaction</h3>
                <form method="POST" action="<?php echo url('add_transaction.php'); ?>">
                    <input type="hidden" name="budget_id" value="<?php echo $budget ? $budget['id_budget'] : ''; ?>">
                    
                    <div class="form-group">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" required class="form-select">
                            <option value="depense">Dépense</option>
                            <option value="revenu">Revenu</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="categorie" class="form-label">Catégorie</label>
                        <input 
                            type="text" 
                            id="categorie" 
                            name="categorie" 
                            class="form-input" 
                            placeholder="Alimentation, Transport, etc."
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="montant" class="form-label">Montant (MAD)</label>
                        <input 
                            type="number" 
                            id="montant" 
                            name="montant" 
                            step="0.01" 
                            required 
                            class="form-input" 
                            placeholder="0.00"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="date_tx" class="form-label">Date</label>
                        <input 
                            type="date" 
                            id="date_tx" 
                            name="date_tx" 
                            required 
                            class="form-input"
                            value="<?php echo date('Y-m-d'); ?>"
                        >
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full">
                        Ajouter la transaction
                    </button>
                </form>
            </div>

            <div class="glass-card">
                <h3 style="margin-bottom: 1.5rem;">Résumé du budget</h3>
                
                <?php if ($budget): ?>
                    <div style="margin-bottom: 1rem;">
                        <div class="stat-label">Objectif d'épargne</div>
                        <div style="font-size: 1.5rem; font-weight: 600; color: var(--color-text-primary);">
                            <?php echo number_format($budget['objectif'], 2); ?> MAD
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <div class="stat-label">Période</div>
                        <div style="color: var(--color-text-secondary);">
                            <?php echo htmlspecialchars($budget['periode_debut']); ?> 
                            → 
                            <?php echo htmlspecialchars($budget['periode_fin']); ?>
                        </div>
                    </div>
                    
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--glass-border);">
                        <div class="stat-label">Progression</div>
                        <?php 
                        $progress = $budget['objectif'] > 0 ? ($budget['epargne'] / $budget['objectif']) * 100 : 0;
                        $progress = min(100, max(0, $progress));
                        ?>
                        <div style="background: rgba(255,255,255,0.05); height: 12px; border-radius: 999px; overflow: hidden; margin-top: 0.5rem;">
                            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100%; width: <?php echo $progress; ?>%; transition: width 0.3s ease;"></div>
                        </div>
                        <div style="margin-top: 0.5rem; color: var(--color-text-muted); font-size: 0.875rem;">
                            <?php echo number_format($progress, 1); ?>% de l'objectif atteint
                        </div>
                    </div>
                <?php else: ?>
                    <p style="color: var(--color-text-secondary);">
                        Vous n'avez pas encore de budget. Créez-en un pour commencer à suivre vos finances.
                    </p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
    // Prepare data for Chart.js
    const txData = <?php echo json_encode($transactions); ?>;
    
    // Group transactions by date
    const dateMap = new Map();
    txData.forEach(t => {
        if (!dateMap.has(t.date_tx)) {
            dateMap.set(t.date_tx, { revenus: 0, depenses: 0 });
        }
        const data = dateMap.get(t.date_tx);
        if (t.type === 'revenu') {
            data.revenus += parseFloat(t.montant);
        } else {
            data.depenses += parseFloat(t.montant);
        }
    });
    
    // Convert to arrays for Chart.js
    const labels = Array.from(dateMap.keys()).sort();
    const revenus = labels.map(date => dateMap.get(date).revenus);
    const depenses = labels.map(date => dateMap.get(date).depenses);
    
    // Create chart
    const ctx = document.getElementById('txChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenus',
                    data: revenus,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                },
                {
                    label: 'Dépenses',
                    data: depenses,
                    backgroundColor: 'rgba(239, 68, 68, 0.6)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#f8fafc',
                        font: {
                            family: 'Inter',
                            size: 12
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#cbd5e1'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#cbd5e1'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });
    </script>
</body>
</html>
