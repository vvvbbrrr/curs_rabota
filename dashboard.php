<?php
require_once('includes/db.php');
require_once('includes/auth.php');
require_once('includes/portfolio.php');
require_once('includes/orders.php');

requireAuth();

$user_id = $_SESSION['user_id'];
$user_role = getUserRole();
$orders = getUserOrders($user_id, $user_role);

require_once('includes/header.php');
?>

<section class="card">
    <div class="dashboard-header">
        <h2 class="card-title">Личный кабинет</h2>
        
        <?php if ($user_role === 'photographer'): ?>
            <a href="upload.php" class="btn">Добавить фотографию</a>
        <?php endif; ?>
    </div>
    
    <p style="font-size: 1.2rem; margin-bottom: 30px;">Добро пожаловать, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>!</p>
    
    <h3 style="font-size: 1.5rem; margin: 40px 0 20px; color: var(--primary);">История заказов</h3>
    
    <?php if (empty($orders)): ?>
        <p style="font-size: 1.2rem; padding: 20px; text-align: center; background: var(--light-bg); border-radius: 10px;">
            Заказов пока нет
        </p>
    <?php else: ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <?php if ($user_role === 'client'): ?>
                            <th>Фотограф</th>
                        <?php else: ?>
                            <th>Клиент</th>
                        <?php endif; ?>
                        <th>Тематика</th>
                        <th>Комментарий</th>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order[$user_role === 'client' ? 'photographer_name' : 'client_name']) ?></td>
                            <td><?= htmlspecialchars($order['theme']) ?></td>
                            <td><?= htmlspecialchars($order['comments']) ?></td>
                            <td><?= date('d.m.Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <?php if ($order['status'] === 'pending'): ?>
                                    <span style="color: #ffc107; font-weight: 500;">Ожидает</span>
                                <?php elseif ($order['status'] === 'accepted'): ?>
                                    <span style="color: #17a2b8; font-weight: 500;">Принят</span>
                                <?php else: ?>
                                    <span style="color: #28a745; font-weight: 500;">Завершен</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <?php if ($user_role === 'photographer'): ?>
                                        <?php if ($order['status'] === 'pending'): ?>
                                            <button class="action-btn accept" onclick="location.href='update_order.php?id=<?= $order['id'] ?>&status=accepted'">Принять</button>
                                            <button class="action-btn delete" onclick="if(confirm('Вы уверены, что хотите удалить заказ?')) location.href='delete_order.php?id=<?= $order['id'] ?>'">Удалить</button>
                                        <?php elseif ($order['status'] === 'accepted'): ?>
                                            <button class="action-btn complete" onclick="location.href='update_order.php?id=<?= $order['id'] ?>&status=completed'">Завершить</button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($order['status'] === 'pending'): ?>
                                            <button class="action-btn delete" onclick="if(confirm('Вы уверены, что хотите отменить заказ?')) location.href='delete_order.php?id=<?= $order['id'] ?>'">Отменить</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require_once('includes/footer.php'); ?>