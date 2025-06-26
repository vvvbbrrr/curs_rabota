<?php
require_once('includes/db.php');
require_once('includes/auth.php');
require_once('includes/orders.php');

requireAuth();
if (getUserRole() !== 'client') {
    header('Location: index.php');
    exit;
}

$photographer_id = $_GET['photographer_id'] ?? 0;

// Проверка существования фотографа
$stmt = $db->prepare('SELECT id FROM users WHERE id = :id AND role = "photographer"');
$stmt->bindValue(':id', $photographer_id, SQLITE3_INTEGER);
$result = $stmt->execute();
if (!$result->fetchArray()) {
    header('Location: index.php');
    exit;
}

// Обработка формы
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = trim($_POST['theme']);
    $comments = trim($_POST['comments']);
    
    if (empty($theme)) {
        $error = 'Укажите тематику фотосессии';
    } else {
        if (placeOrder($photographer_id, $_SESSION['user_id'], $theme, $comments)) {
            $_SESSION['success'] = 'Заказ успешно оформлен!';
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Ошибка при оформлении заказа';
        }
    }
}

require_once('includes/header.php');
?>

<section class="card">
    <h2 class="card-title">Оформление заказа</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="theme">Тематика фотосессии</label>
            <input type="text" id="theme" name="theme" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="comments">Дополнительные пожелания</label>
            <textarea id="comments" name="comments" class="form-control" rows="4"></textarea>
        </div>
        
        <button type="submit" class="btn btn-large">Подтвердить заказ</button>
    </form>
</section>

<?php require_once('includes/footer.php'); ?>