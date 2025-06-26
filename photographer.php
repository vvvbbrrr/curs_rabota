<?php
require_once('includes/db.php');
require_once('includes/auth.php');
require_once('includes/portfolio.php');

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$photographer_id = $_GET['id'];
$portfolio = getPortfolio($photographer_id);

// Получаем информацию о фотографе
$stmt = $db->prepare('SELECT name, phone FROM users WHERE id = :id AND role = "photographer"');
$stmt->bindValue(':id', $photographer_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$photographer = $result->fetchArray(SQLITE3_ASSOC);

if (!$photographer) {
    header('Location: index.php');
    exit;
}

require_once('includes/header.php');
?>

<section class="card">
    <h2 class="card-title">Портфолио фотографа: <?= htmlspecialchars($photographer['name']) ?></h2>
    <p>Телефон: <?= htmlspecialchars($photographer['phone']) ?></p>
    
    <div class="portfolio-grid">
        <?php foreach ($portfolio as $item): ?>
            <div class="portfolio-item">
                <img src="<?= htmlspecialchars($item['photo_path']) ?>" alt="<?= htmlspecialchars($item['description']) ?>">
                <div class="portfolio-desc"><?= htmlspecialchars($item['description']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (isLoggedIn() && getUserRole() === 'client'): ?>
        <a href="place_order.php?photographer_id=<?= $photographer_id ?>" class="btn btn-large">Заказать фотосессию</a>
    <?php endif; ?>
</section>

<?php require_once('includes/footer.php'); ?>