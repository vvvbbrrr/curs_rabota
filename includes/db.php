<?php
session_start();

// Конфигурация базы данных
define('DB_FILE', __DIR__ . '/../photographers.db');

// Создание базы данных при первом запуске
if (!file_exists(DB_FILE)) {
    $db = new SQLite3(DB_FILE);
    
    // Создание таблиц
    $db->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL, -- photographer или client
        phone TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    
    $db->exec('CREATE TABLE IF NOT EXISTS portfolio (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        photographer_id INTEGER NOT NULL,
        photo_path TEXT NOT NULL,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (photographer_id) REFERENCES users(id) ON DELETE CASCADE
    )');
    
    $db->exec('CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        photographer_id INTEGER NOT NULL,
        client_id INTEGER NOT NULL,
        theme TEXT NOT NULL,
        comments TEXT,
        status TEXT DEFAULT "pending", -- pending, accepted, completed
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (photographer_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE
    )');
    
    $db->close();
}

// Подключение к БД
$db = new SQLite3(DB_FILE);
$db->enableExceptions(true);
?>