<?php
require_once('db.php');

function placeOrder($photographer_id, $client_id, $theme, $comments) {
    global $db;
    
    $stmt = $db->prepare('INSERT INTO orders (photographer_id, client_id, theme, comments) 
                          VALUES (:photographer_id, :client_id, :theme, :comments)');
    $stmt->bindValue(':photographer_id', $photographer_id, SQLITE3_INTEGER);
    $stmt->bindValue(':client_id', $client_id, SQLITE3_INTEGER);
    $stmt->bindValue(':theme', $theme, SQLITE3_TEXT);
    $stmt->bindValue(':comments', $comments, SQLITE3_TEXT);
    
    return $stmt->execute();
}

function getUserOrders($user_id, $role) {
    global $db;
    $orders = [];
    
    if ($role === 'client') {
        $stmt = $db->prepare('SELECT o.*, u.name AS photographer_name 
                              FROM orders o
                              JOIN users u ON o.photographer_id = u.id
                              WHERE o.client_id = :user_id
                              ORDER BY o.created_at DESC');
    } else {
        $stmt = $db->prepare('SELECT o.*, u.name AS client_name 
                              FROM orders o
                              JOIN users u ON o.client_id = u.id
                              WHERE o.photographer_id = :user_id
                              ORDER BY o.created_at DESC');
    }
    
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $orders[] = $row;
    }
    return $orders;
}

function updateOrderStatus($order_id, $status) {
    global $db;
    
    $stmt = $db->prepare('UPDATE orders SET status = :status WHERE id = :id');
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
    
    return $stmt->execute();
}

function deleteOrder($order_id) {
    global $db;
    
    $stmt = $db->prepare('DELETE FROM orders WHERE id = :id');
    $stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
    
    return $stmt->execute();
}
?>