<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фотосервис | Профессиональные фотографы</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <h1 class="logo">Фото<span>Сервис</span></h1>
                <nav class="nav">
                    <ul>
                        <li><a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Главная</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>Личный кабинет</a></li>
                            <li><a href="logout.php" class="btn btn-outline">Выйти</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="btn btn-outline">Войти</a></li>
                            <li><a href="register.php" class="btn">Регистрация</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main class="container">