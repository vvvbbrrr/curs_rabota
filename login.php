<?php
require_once('includes/db.php');
require_once('includes/auth.php');

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $result = loginUser($email, $password);
    if ($result === true) {
        header('Location: index.php');
        exit;
    } else {
        $error = $result;
    }
}

require_once('includes/header.php');
?>

<div class="centered">
    <section class="card">
        <h2 class="card-title">Вход в аккаунт</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form-container">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-large">Войти</button>
            
            <p style="text-align: center; margin-top: 25px; font-size: 1.1rem;">
                Нет аккаунта? <a href="register.php" style="color: var(--primary);">Зарегистрируйтесь</a>
            </p>
        </form>
    </section>
</div>

<?php require_once('includes/footer.php'); ?>