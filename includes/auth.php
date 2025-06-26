<?php
require_once('db.php');

function registerUser($name, $email, $password, $role, $phone) {
    global $db;
    
    // Проверка существующего email
    $stmt = $db->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    if ($result->fetchArray()) {
        return 'Пользователь с таким email уже существует';
    }
    
    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Добавление пользователя
    $stmt = $db->prepare('INSERT INTO users (name, email, password, role, phone) 
                          VALUES (:name, :email, :password, :role, :phone)');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    
    if ($stmt->execute()) {
        return true;
    }
    return 'Ошибка при регистрации';
}

function loginUser($email, $password) {
    global $db;
    
    $stmt = $db->prepare('SELECT id, name, password, role FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    return 'Неверный email или пароль';
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requirePhotographer() {
    requireAuth();
    if (getUserRole() !== 'photographer') {
        header('Location: index.php');
        exit;
    }
}
?>