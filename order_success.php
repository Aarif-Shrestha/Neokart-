    <?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: auth/create_login.php");
        exit();
    }

    if (!isset($_SESSION['order_id'])) {
        header("Location: cart.php");
        exit();
    }

    $order_id = $_SESSION['order_id'];
    unset($_SESSION['order_id']);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Successful - NeoKart</title>
        <link rel="stylesheet" href="style/index.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    <body>
        <?php $active_page = ''; ?>
        <?php include 'nav.php'; ?>

        <div class="success-page">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Order Placed Successfully!</h1>
            <p class="order-id">Order ID: <strong>#<?php echo $order_id; ?></strong></p>
            <p class="msg">Thank you for your purchase! Your order has been confirmed and is being processed.</p>
            
            <div class="actions">
                <a href="products.php" class="btn-primary">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
                <a href="cart.php" class="btn-outline">
                    <i class="fas fa-shopping-cart"></i> View Cart
                </a>
            </div>
        </div>

        <?php include 'footer.php'; ?>
    </body>
    </html>
