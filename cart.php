<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ./auth/create_login.php");
    exit();
}


include "config/db_config.php";



$user_id = $_SESSION['id'];

// Handle add to cart from products page
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    $check = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $check_result = $conn->query($check);
    
    if ($check_result->num_rows > 0) {
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $conn->query($query);
    } else {
        $query = "INSERT INTO cart (user_id, product_id, quantity, added_date) VALUES ('$user_id', '$product_id', 1, NOW())";
        $conn->query($query);
    }
    
    header("Location: products.php");
    exit();
}

if (isset($_GET['featured_product_id'])) {
    $product_id = $_GET['featured_product_id'];
    
    $check = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $check_result = $conn->query($check);
    
    if ($check_result->num_rows > 0) {
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $conn->query($query);
    } else {
        $query = "INSERT INTO cart (user_id, product_id, quantity, added_date) VALUES ('$user_id', '$product_id', 1, NOW())";
        $conn->query($query);
    }
    
    header("Location: index.php");
    exit();
}

// Handle cart actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == 'increase' && isset($_GET['cart_id'])) {
        $cart_id = $_GET['cart_id'];
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE cart_id = '$cart_id' AND user_id = '$user_id'";
        $conn->query($query);
        header("Location: cart.php");
        exit();
    }
    
    if ($action == 'decrease' && isset($_GET['cart_id'])) {
        $cart_id = $_GET['cart_id'];
        // Check quantity first
        $check = "SELECT quantity FROM cart WHERE cart_id = '$cart_id' AND user_id = '$user_id'";
        $result = $conn->query($check);
        $row = $result->fetch_assoc();
        
        if ($row['quantity'] > 1) {
            $query = "UPDATE cart SET quantity = quantity - 1 WHERE cart_id = '$cart_id' AND user_id = '$user_id'";
            $conn->query($query);
        } else {
            $query = "DELETE FROM cart WHERE cart_id = '$cart_id' AND user_id = '$user_id'";
            $conn->query($query);
        }
        header("Location: cart.php");
        exit();
    }
    
    if ($action == 'remove' && isset($_GET['cart_id'])) {
        $cart_id = $_GET['cart_id'];
        $query = "DELETE FROM cart WHERE cart_id = '$cart_id' AND user_id = '$user_id'";
        $conn->query($query);
        header("Location: cart.php");
        exit();
    }
    
    if ($action == 'clear') {
        $query = "DELETE FROM cart WHERE user_id = '$user_id'";
        $conn->query($query);
        header("Location: cart.php");
        exit();
    }
}

// Fetch cart items from database
$query = "SELECT cart.cart_id, cart.product_id, cart.quantity, products.name, products.price, products.image, category.category_name 
          FROM cart 
          JOIN products ON cart.product_id = products.product_id 
          JOIN category ON products.category_id = category.category_id 
          WHERE cart.user_id = '$user_id'";
$result = $conn->query($query);

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

$cart_empty = empty($cart_items);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - NeoKart</title>
    <link rel="stylesheet" href="style/index.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php $active_page = 'cart'; ?>
    <?php include 'nav.php'; ?>

    <div class="products-container">
        <?php if (isset($_SESSION['order_error'])) { ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['order_error']; ?>
            </div>
            <?php unset($_SESSION['order_error']); ?>
        <?php } ?>
        
        <div class="products-header">
            <h1>Shopping Cart</h1>
            <?php if (!$cart_empty) { ?>
                <a href="cart.php?action=clear" class="clear-cart-link" onclick="return confirm('Are you sure you want to clear your cart?')">Clear Cart</a>
            <?php } ?>
        </div>

        <?php if ($cart_empty) { ?>
            <!-- Empty Cart State -->
            <div class="empty-cart">
                <div class="empty-cart-icon-wrapper">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h1>Your cart is empty</h1>
                <p>Add some delicious items to your cart!</p>
                <a href="products.php" class="btn-primary">Browse Products <i class="fas fa-arrow-right"></i></a>
            </div>
        <?php } else { ?>
            <!-- Cart with Items -->
            <div class="cart-layout">
                
                <!-- Cart Items -->
                <div>
                    <?php foreach ($cart_items as $item) { ?>
                        <div class="card cart-item">
                            <img src="new_img/<?php echo $item['image']; ?>" alt="" class="cart-item-image">
                            <div class="cart-item-info">
                                <h3><?php echo $item['name']; ?></h3>
                                <p><?php echo $item['category_name']; ?></p>
                            </div>
                            <div class="cart-quantity">
                                <a href="cart.php?action=decrease&cart_id=<?php echo $item['cart_id']; ?>">
                                    <button class="quantity-btn">-</button>
                                </a>
                                <span class="quantity-value"><?php echo $item['quantity']; ?></span>
                                <a href="cart.php?action=increase&cart_id=<?php echo $item['cart_id']; ?>">
                                    <button class="quantity-btn">+</button>
                                </a>
                            </div>
                            <span class="price cart-item-price">Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            <a href="cart.php?action=remove&cart_id=<?php echo $item['cart_id']; ?>" onclick="return confirm('Remove this item from cart?')">
                                <button class="cart-remove-btn"><i class="fas fa-trash"></i></button>
                            </a>
                        </div>
                    <?php } ?>
                </div>

                <!-- Order Summary -->
                <div class="card order-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart_items)), 2); ?></span>
                    </div>
                    <div class="summary-row summary-delivery">
                        <span>Delivery</span>
                        <span>Free</span>
                    </div>
                    <hr class="summary-divider">
                    <div class="summary-total">
                        <span>Total</span>
                        <span class="summary-total-price">Rs. <?php echo number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart_items)), 2); ?></span>
                    </div>
                    <form action="checkout.php" method="POST">
                        <button type="submit" class="btn-primary checkout-btn">Proceed to Checkout <i class="fas fa-arrow-right"></i>
                        </button>
                        <!-- <a href="products.php" class="continue-shopping">Continue Shopping</a> -->
                    </form>
                        <!-- <a href="checkout.php" class="btn-primary checkout-btn">Proceed to Checkout <i class="fas fa-arrow-right"></i></a> -->
                         <a href="products.php" class="continue-shopping">Continue Shopping</a>
                </div>

            </div>
        <?php } ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
