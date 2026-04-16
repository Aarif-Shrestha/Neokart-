<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ./auth/create_login.php");
    exit();
}

if (!$_SESSION['is_admin']) {
    header("Location: ../index.php");
    exit();
}

include "../config/db_config.php";


$products_query = "SELECT COUNT(*) as total FROM products";
$products_result = $conn->query($products_query);
$total_products = $products_result->fetch_assoc()['total'];

$orders_query = "SELECT COUNT(*) as total FROM orders";
$orders_result = $conn->query($orders_query);
$total_orders = $orders_result->fetch_assoc()['total'];

$users_query = "SELECT COUNT(*) as total FROM users";
$users_result = $conn->query($users_query);
$total_users = $users_result->fetch_assoc()['total'];

$revenue_query = "SELECT SUM(total_amount) as revenue FROM orders";
$revenue_result = $conn->query($revenue_query);
$total_revenue = $revenue_result->fetch_assoc()['revenue'] ?? 0;
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>
    <nav>
        <h1>Admin Panel</h1>
        <a href="admin.php" class="active">📊 Dashboard</a>
        <a href="products.php">📦 Products</a>
        <a href="orders.php">🛍️ Orders</a>
        <a href="users.php">👥 Users</a>
        
    </nav>
    
    <section>
        <div class="top-header">
        <div class="sub-header">
            <h2>Dashboard</h2>
            <p>Welcome to the admin dashboard</p>
        </div>

        <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>
        
        <table>
            <tr>
                <td>Total Products<br><strong><?php echo $total_products; ?></strong></td>
                <td>Total Orders<br><strong><?php echo $total_orders; ?></strong></td>
                <td>Total Users<br><strong><?php echo $total_users; ?></strong></td>
                <td>Revenue<br><strong>Rs. <?php echo number_format($total_revenue, 2); ?></strong></td>
            </tr>
        </table>
    </section>
</body>
</html>
