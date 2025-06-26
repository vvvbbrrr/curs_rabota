<?php
require_once('includes/db.php');
require_once('includes/auth.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $phone = trim($_POST['phone']);
    
    $result = registerUser($name, $email, $password, $role, $phone);
    if ($result === true) {
        $_SESSION['success'] = 'Регистрация успешна! Теперь войдите в систему.';
        header('Location: login.php');
        exit;
    } else {
        $error = $result;
    }
}

require_once('includes/header.php');
?>

<div class="centered">
    <section class="card">
        <h2 class="card-title">Создать аккаунт</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form-container">
            <div class="form-group">
                <label for="name">Полное имя</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Телефон</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Вы регистрируетесь как:</label>
                <div class="role-selector">
                    <div class="role-option" data-role="client">
                        <input type="radio" id="client" name="role" value="client" checked class="role-radio">
                        <label for="client">Клиент</label>
                        <p>Хочу заказывать фотосессии</p>
                    </div>
                    
                    <div class="role-option" data-role="photographer">
                        <input type="radio" id="photographer" name="role" value="photographer" class="role-radio">
                        <label for="photographer">Фотограф</label>
                        <p>Хочу предлагать услуги</p>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-large">Создать аккаунт</button>
            
            <p style="text-align: center; margin-top: 25px; font-size: 1.1rem;">
                Уже есть аккаунт? <a href="login.php" style="color: var(--primary);">Войти</a>
            </p>
        </form>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Выбор роли
    const roleOptions = document.querySelectorAll('.role-option');
    
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Удаляем выделение со всех опций
            roleOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Добавляем выделение к текущей опции
            this.classList.add('selected');
            
            // Устанавливаем значение radio
            const radio = this.querySelector('.role-radio');
            radio.checked = true;
        });
    });
    
    // Выделяем выбранную по умолчанию опцию
    document.querySelector('.role-option[data-role="client"]').classList.add('selected');
});
</script>

<?php require_once('includes/footer.php'); ?>