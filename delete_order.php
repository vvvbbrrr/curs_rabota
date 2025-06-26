<?php
require_once('includes/db.php');
require_once('includes/auth.php');
require_once('includes/orders.php');

requireAuth();

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    // Проверяем, принадлежит ли заказ текущему пользователю
    $stmt = $db->prepare('SELECT * FROM orders WHERE id = :id');
    $stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $order = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($order) {
        $user_role = getUserRole();
        $user_id = $_SESSION['user_id'];
        
        if (($user_role === 'photographer' && $order['photographer_id'] == $user_id) ||
            ($user_role === 'client' && $order['client_id'] == $user_id)) {
            
            if (deleteOrder($order_id)) {
                $_SESSION['success'] = 'Заказ успешно удален!';
            } else {
                $_SESSION['error'] = 'Ошибка при удалении заказа';
            }
        }
    }
}

header('Location: dashboard.php');
exit;
?>