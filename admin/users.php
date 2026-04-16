<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ./auth/create_login.php");
    exit();
}
?>


<?php
    include "../config/db_config.php";

    $query = "SELECT * FROM users ORDER BY is_admin DESC, name ASC";
    $result = $conn->query($query);

?>

<?php

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin Panel</title>
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>
    <nav>
        <h1>Admin Panel</h1>
        <a href="admin.php">📊 Dashboard</a>
        <a href="products.php">📦 Products</a>
        <a href="orders.php">🛍️ Orders</a>
        <a href="users.php" class="active">👥 Users</a>
    </nav>
    
    <section>
        <h2>Users</h2>
        <p>Manage user accounts</p>

        <br>
        
        <table>
            <thead>
                <tr>
                    <th>USER</th>
                    <th>EMAIL</th>
                    <th>Phone</th>
                    <th>Admin</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['user_id'];
                        $name = $row['name'];
                        $email = $row['email'];
                        $Phone = $row['phone'];
                        $is_admin = $row['is_admin'];

                    ?>
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $email; ?></td>
                        <td><?php echo $Phone; ?></td>
                         <td><?php echo $is_admin ? 'Admin' : 'User'; ?></td>
                        <td class="actions">
                            <a href="users.php?id=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to delete this user?');"><button class="btn-icon">Delete</button></a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
            </tbody>
        </table>
    </section>
</body>
</html>
