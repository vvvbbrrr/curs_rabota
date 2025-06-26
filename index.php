<?php
require_once('includes/db.php');
require_once('includes/header.php');
require_once('includes/portfolio.php');

$photographers = getPhotographers();
?>

<section class="card">
    <h2 class="card-title">Наши фотографы</h2>
    <div class="photographers-grid">
        <?php foreach ($photographers as $photographer): ?>
            <div class="photographer-card">
                <div class="photographer-img <?= empty($photographer['avatar']) ? 'default-avatar' : '' ?>">
                    <?php if (!empty($photographer['avatar'])): ?>
                        <img src="<?= htmlspecialchars($photographer['avatar']) ?>" alt="<?= htmlspecialchars($photographer['name']) ?>">
                    <?php else: ?>
                        <i class="fas fa-camera"></i>
                    <?php endif; ?>
                </div>
                <div class="photographer-info">
                    <h3><?= htmlspecialchars($photographer['name']) ?></h3>
                    <p><?= htmlspecialchars($photographer['phone']) ?></p>
                    <a href="photographer.php?id=<?= $photographer['id'] ?>" class="btn">Посмотреть портфолио</a>
                </div>
            </div>    
        <?php endforeach; ?>
    </div>
</section>

<?php require_once('includes/footer.php'); ?>