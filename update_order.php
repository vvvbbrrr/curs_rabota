<?php
require_once('includes/db.php');
require_once('includes/auth.php');
require_once('includes/orders.php');

requirePhotographer();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = $_GET['id'];
    $status = $_GET['status'];
    
    if (in_array($status, ['accepted', 'completed'])) {
        updateOrderStatus($order_id, $status);
        $_SESSION['success'] = 'Статус заказа обновлен!';
    }
}

header('Location: dashboard.php');
exit;
?>