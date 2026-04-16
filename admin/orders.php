<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ./auth/create_login.php");
    exit();
}
?>


<?php
    include "../config/db_config.php";

    $query = "SELECT orders.*, users.name, products.name AS product_name ,order_item.quantity FROM orders LEFT JOIN users ON orders.user_id = users.user_id LEFT JOIN order_item ON orders.order_id = order_item.order_id LEFT JOIN products ON order_item.product_id = products.product_id ORDER BY orders.order_id";
    $result = $conn->query($query);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Admin Panel</title>
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>
    <nav>
        <h1>Admin Panel</h1>
        <a href="admin.php">📊 Dashboard</a>
        <a href="products.php">📦 Products</a>
        <a href="orders.php" class="active">🛍️ Orders</a>
        <a href="users.php">👥 Users</a>
    </nav>
    
    <section>
        <h2>Orders</h2>
        <p>Manage customer orders</p>

        <br>

        
        <table>
            <thead>
                <tr>
                    <th>ORDER ID</th>
                    <th>CUSTOMER</th>
                    <th>ITEMS</th>
                    <th>DATE</th>
                    <th>QUANTITY</th>
                    <th>TOTAL</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['order_id'];
                        $user_id = $row['user_id'];
                        $name = $row['name'];
                        $items = $row['product_name'];
                        $date = $row['order_date'];
                        $amount = $row['total_amount'];
                        $status = $row['order_status'];
                        $quantity = $row['quantity'];
                    ?>
                    <tr>
                        <td><?php echo $id;?></td>
                        <td><?php echo $name;?></td>
                        <td><?php echo $items;?></td>
                        <td><?php echo $date;?></td>
                        <td><?php  echo $quantity;?></td>
                        <td><?php echo $amount;?></td>
                        <td><?php echo $status;?></td>

                    </tr>

                    <?php    
                    }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>


<!-- <tr>    
                    <td>#1</td>
                    <td>
                        <div class="customer-cell">
                            <strong>John Doe<br><span>john@example.com</span></strong>
                        </div>
                    </td>
                    <td>2024-01-15</td>
                    <td>3 items</td>
                    <td>$45.97</td>
                    <td><span class="badge success">Delivered</span></td>
                    <td class="actions">
                        <button class="btn-icon">👁️</button>
                    </td>
                </tr>
                <tr>
                    <td>#2</td>
                    <td>
                        <div class="customer-cell">
                            <strong>Jane Smith<br><span>jane@example.com</span></strong>
                            
                        </div>
                    </td>
                    <td>2024-01-14</td>
                    <td>2 items</td>
                    <td>$28.99</td>
                    <td><span class="badge warning">Shipped</span></td>
                    <td class="actions">
                        <button class="btn-icon">👁️</button>
                    </td>
                </tr>
                <tr>
                    <td>#3</td>
                    <td>
                        <div class="customer-cell">
                            <strong>Bob Wilson<br><span>bob@example.com</span></strong>
                            
                        </div>
                    </td>
                    <td>2024-01-13</td>
                    <td>4 items</td>
                    <td>$67.50</td>
                    <td><span class="badge info">Processing</span></td>
                    <td class="actions">
                        <button class="btn-icon">👁️</button>
                    </td>
                </tr>
                <tr>
                    <td>#4</td>
                    <td>
                        <div class="customer-cell">
                            <strong>Alice Brown<br><span>alice@example.com</span></strong>
                           
                        </div>
                    </td>
                    <td>2024-01-12</td>
                    <td>2 items</td>
                    <td>$32.00</td>
                    <td><span class="badge pending">Pending</span></td>
                    <td class="actions">
                        <button class="btn-icon">👁️</button>
                    </td>
                </tr> -->