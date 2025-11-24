        <a href="<?php echo url('community.php'); ?>" style="display: inline-block; margin-bottom: 1.5rem; color: var(--color-primary-light);">
            ← Retour à la communauté
        </a>

        <!-- Post Detail -->
        <article class="glass-card fade-in" style="margin-bottom: 2rem;">
            <div class="post-header">
                <div>
                    <strong class="post-author"><?php echo htmlspecialchars($post['author']); ?></strong>
                    <span style="margin-left: 0.5rem; padding: 0.25rem 0.75rem; background: var(--glass-bg); border-radius: var(--radius-full); font-size: 0.75rem; color: var(--color-text-muted);">
                        <?php echo htmlspecialchars($post['type']); ?>
                    </span>
                </div>
                <small class="post-date"><?php echo htmlspecialchars($post['created_at']); ?></small>
            </div>
            
            <?php if($post['titre']): ?>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem; color: var(--color-text-primary);">
                    <?php echo htmlspecialchars($post['titre']); ?>
                </h1>
            <?php endif; ?>
            
            <div style="color: var(--color-text-secondary); line-height: 1.7; margin-bottom: 1.5rem;">
                <?php echo nl2br(htmlspecialchars($post['contenu'])); ?>
            </div>
            
            <div style="padding-top: 1rem; border-top: 1px solid var(--glass-border);">
                <span style="color: var(--color-text-muted); font-size: 0.875rem;">
                    ❤️ <?php echo $post['likes_count']; ?> j'aime
                </span>
            </div>
        </article>

        <!-- Comments Section -->
        <section class="glass-card fade-in">
            <h2 style="margin-bottom: 1.5rem;">
                Commentaires (<?php echo count($comments); ?>)
            </h2>
            
            <!-- Comment Form -->
            <form method="POST" action="" style="margin-bottom: 2rem;">
                <div class="form-group">
                    <label for="contenu" class="form-label">Ajouter un commentaire</label>
                    <textarea 
                        id="contenu" 
                        name="contenu" 
                        required 
                        class="form-textarea" 
                        placeholder="Partagez votre avis..."
                        style="min-height: 100px;"
                    ></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    Envoyer
                </button>
            </form>
            
            <!-- Comments List -->
            <?php if (empty($comments)): ?>
                <div style="text-align: center; padding: 2rem; color: var(--color-text-muted);">
                    Aucun commentaire pour le moment. Soyez le premier à commenter !
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach($comments as $c): ?>
                        <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: var(--radius-lg); padding: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <strong style="color: var(--color-text-primary);">
                                    <?php echo htmlspecialchars($c['author']); ?>
                                </strong>
                                <small style="color: var(--color-text-muted); font-size: 0.875rem;">
                                    <?php echo htmlspecialchars($c['created_at']); ?>
                                </small>
                            </div>
                            <p style="color: var(--color-text-secondary); line-height: 1.6;">
                                <?php echo nl2br(htmlspecialchars($c['contenu'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>