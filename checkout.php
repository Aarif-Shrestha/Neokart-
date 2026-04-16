<?php
session_start();
include "config/db_config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cart.php");
    exit();
}

if (!isset($_SESSION['id'])) {
    header("Location: auth/create_login.php");
    exit();
}

$user_id = (int)$_SESSION['id'];

/* 1️⃣ Fetch cart items WITH product price */
$cart_sql = "
    SELECT cart.product_id, cart.quantity, products.price
    FROM cart
    JOIN products ON cart.product_id = products.product_id
    WHERE cart.user_id = $user_id
";
$cart_result = $conn->query($cart_sql);

if (!$cart_result || $cart_result->num_rows === 0) {
    header("Location: cart.php");
    exit();
}

/* 2️⃣ Start transaction */
$conn->begin_transaction();

try {

    /* 3️⃣ Create order FIRST */
    $order_sql = "
        INSERT INTO orders (user_id, total_amount, order_status, order_date)
        VALUES ($user_id, 0, 'Pending', NOW())
    ";

    if (!$conn->query($order_sql)) {
        throw new Exception("Order insert failed");
    }

    $order_id = $conn->insert_id;

    /* 4️⃣ Insert order items + calculate total */
    $total_amount = 0;

    while ($row = $cart_result->fetch_assoc()) {

        $product_id = (int)$row['product_id'];
        $quantity   = (int)$row['quantity'];
        $price      = (float)$row['price'];

        $subtotal = $price * $quantity;
        $total_amount += $subtotal;

        $item_sql = "
            INSERT INTO order_item (order_id, product_id, quantity, price)
            VALUES ($order_id, $product_id, $quantity, $price)
        ";

        if (!$conn->query($item_sql)) {
            throw new Exception("Order item insert failed");
        }
    }

    /* 5️⃣ Update total_amount in orders */
    $update_total = "
        UPDATE orders
        SET total_amount = $total_amount
        WHERE order_id = $order_id
    ";

    if (!$conn->query($update_total)) {
        throw new Exception("Order total update failed");
    }

    /* 6️⃣ Clear cart */
    $clear_cart = "DELETE FROM cart WHERE user_id = $user_id";
    if (!$conn->query($clear_cart)) {
        throw new Exception("Cart clear failed");
    }

    /* 7️⃣ Commit */
    $conn->commit();

    $_SESSION['order_id'] = $order_id;
    header("Location: order_success.php");
    exit();

} catch (Exception $e) {

    $conn->rollback();
    $_SESSION['order_error'] = "Checkout failed. Please try again.";
    header("Location: cart.php");
    exit();
}
?>
