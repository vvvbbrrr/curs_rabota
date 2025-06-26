<?php
require_once('db.php');

function getPhotographers() {
    global $db;
    $photographers = [];
    
    $result = $db->query('SELECT id, name, phone FROM users WHERE role = "photographer"');
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        
        // добавляем первую фотографию из портфолио как аватар
        $portfolio = getPortfolio($row['id']);
        $row['avatar'] = !empty($portfolio) ? $portfolio[0]['photo_path'] : 'assets/images/default-avatar.jpg';
        $photographers[] = $row;
    }
    return $photographers;
}

function getPortfolio($photographer_id) {
    global $db;
    $portfolio = [];
    
    $stmt = $db->prepare('SELECT * FROM portfolio WHERE photographer_id = :id');
    $stmt->bindValue(':id', $photographer_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $portfolio[] = $row;
    }
    return $portfolio;
}

function uploadPhoto($photographer_id, $photo_path, $description) {
    global $db;
    
    $stmt = $db->prepare('INSERT INTO portfolio (photographer_id, photo_path, description) 
                          VALUES (:photographer_id, :photo_path, :description)');
    $stmt->bindValue(':photographer_id', $photographer_id, SQLITE3_INTEGER);
    $stmt->bindValue(':photo_path', $photo_path, SQLITE3_TEXT);
    $stmt->bindValue(':description', $description, SQLITE3_TEXT);
    
    return $stmt->execute();
}
?>