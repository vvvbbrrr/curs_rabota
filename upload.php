<?php
require_once('includes/db.php');
require_once('includes/auth.php');
require_once('includes/portfolio.php');

requirePhotographer();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description']);
    
    if (empty($description)) {
        $error = 'Добавьте описание фотографии';
    } elseif (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Ошибка при загрузке файла';
    } else {
        $upload_dir = 'assets/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $file_name = 'photo_' . time() . '.' . $file_ext;
        $file_path = $upload_dir . $file_name;
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['photo']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $file_path)) {
                if (uploadPhoto($_SESSION['user_id'], $file_path, $description)) {
                    $success = 'Фотография успешно добавлена!';
                } else {
                    $error = 'Ошибка при сохранении в базу данных';
                }
            } else {
                $error = 'Ошибка при сохранении файла';
            }
        } else {
            $error = 'Недопустимый формат файла. Разрешены: JPG, PNG, GIF, WEBP';
        }
    }
}

require_once('includes/header.php');
?>

<section class="card">
    <h2 class="card-title">Добавление фотографии</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="upload-form">
        <div class="form-group">
            <label for="photo">Выберите фотографию</label>
            <input type="file" id="photo" name="photo" class="form-control" required
                   accept="image/jpeg, image/png, image/gif, image/webp">
        </div>
        
        <div class="form-group">
            <label for="description">Описание фотографии</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn btn-large">Загрузить фото</button>
            <a href="dashboard.php" class="btn btn-large btn-outline">Отмена</a>
        </div>
    </form>
</section>

<?php require_once('includes/footer.php'); ?>