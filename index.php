<?php
session_start();
?>

<?php
    include "config/db_config.php";

    $query = 'SELECT * FROM products';
    $sql = $conn->query($query);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoKart - Home</title>
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php $active_page = 'home'; ?>
    <?php include 'nav.php'; ?>
    
    <div class="hero">
        <div class = 'sub-hero'>
            <div class="hero-content">
                <span class="tag">NEW ARRIVALS</span>
                <h1>Elevate Your<br><span class="highlight">Everyday</span></h1>
                <p>Discover premium clothing, cutting-edge electronics, and bestselling books. Everything you need, curated with care.</p>
                <div class="hero-buttons">
                    <a href="products.php" class="btn-primary">Shop Collection</a>
                    <a href="about.php" class="btn-secondary">Learn More</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/tv.jpg" alt="Featured Products">
            </div>
        </div>
    </div>

    <div class="features">
        <div>
            <h3>Fast Delivery</h3>
            <p>Get your items delivered within 30 minutes</p>
        </div>
        <div>
            <h3>Quality Guaranteed</h3>
            <p>Premium products sourced from trusted suppliers</p>
        </div>
        <div>
            <h3>24/7 Service</h3>
            <p>Order anytime, we're always available</p>
        </div>
    </div>

    <div class="category-section">
        <h2>Shop by Category</h2>
        <p class="category-subtitle">Discover what you love</p>
        
        <div class="category-grid">
            <a href="products.php?cat=breakfast" class="category-card">
                <img src="assets/clothing.jpg" alt="Clothing">
                <div class="category-overlay">
                    <h3>Clothing</h3>
                    <span class="explore-btn">Explore Collection →</span>
                </div>
            </a>
            
            <a href="products.php?cat=lunch" class="category-card">
                <img src="assets/electronics.jpg" alt="Electronics">
                <div class="category-overlay">
                    <h3>Electronics</h3>
                    <span class="explore-btn">Explore Collection →</span>
                </div>
            </a>
            
            <a href="products.php?cat=dinner" class="category-card">
                <img src="assets/books.jpg" alt="Books">
                <div class="category-overlay">
                    <h3>Books</h3>
                    <span class="explore-btn">Explore Collection →</span>
                </div>
            </a>
        </div>
    </div>

    <div class="products">
        <h2>Featured Products</h2>
        <p class="subtitle">Our most popular items this week</p>
        
        <div class="product-grid">
            <?php
                $count = 0;
                while ($row = $sql->fetch_assoc()) {
                    if ($count == 8) {
                        break;
                    }
                    $name = $row['name']; 
                    $description = $row['description'];
                    $price = $row['price'];
                    $image = $row['image'];
                    $count++;

                   
            ?>
                <div class="card">
                    <?php echo "<img src='new_img/$image' alt='';'>"; ?>
                    <h3><?php echo $name; ?></h3>
                    <p><?php echo $description; ?></p>
                    <span class="price"><?php echo "Rs " . $price; ?></span>
                    <a href="<?php echo isset($_SESSION['user']) ? 'cart.php?featured_product_id=' . $row['product_id'] : './auth/create_login.php'; ?>"><button>Add to Cart</button></a>
                </div>
            <?php
                }
            
            ?>

            <!-- <div class="card">
                <img src="new_img/bowl1.jpg" alt="Grilled Chicken Bowl">
                <h3>Grilled Chicken Bowl</h3>
                <p>Tender grilled chicken with rice, vegetables, and teriyaki sauce</p>
                <span class="price">$15.99</span>
                <button>Add to Cart</button>
            </div>

            <div class="card">
                <img src="new_img/pizza1.jpg" alt="Margherita Pizza">
                <h3>Margherita Pizza</h3>
                <p>Classic pizza with fresh mozzarella, tomatoes, and basil</p>
                <span class="price">$18.99</span>
                <button>Add to Cart</button>
            </div>

            <div class="card">
                <img src="new_img/burger1.jpg" alt="Veggie Burger">
                <h3>Veggie Burger</h3>
                <p>Plant-based patty with lettuce, tomato, and special sauce</p>
                <span class="price">$13.99</span>
                <button>Add to Cart</button>
            </div>

            <div class="card">
                <img src="new_img/pasta1.jpg" alt="Creamy Pasta">
                <h3>Creamy Alfredo Pasta</h3>
                <p>Fettuccine in rich creamy sauce with parmesan cheese</p>
                <span class="price">$14.99</span>
                <button>Add to Cart</button>
            </div>

            <div class="card">
                <img src="new_img/sushi1.jpg" alt="Sushi Platter">
                <h3>Sushi Platter</h3>
                <p>Assorted sushi rolls with wasabi and soy sauce</p>
                <span class="price">$22.99</span>
                <button>Add to Cart</button>
            </div>

            <div class="card">
                <img src="new_img/dessert1.jpg" alt="Chocolate Cake">
                <h3>Chocolate Cake</h3>
                <p>Rich chocolate cake with ganache frosting</p>
                <span class="price">$8.99</span>
                <button>Add to Cart</button>
            </div>

            <div class="card">
                <img src="new_img/smoothie1.jpg" alt="Berry Smoothie">
                <h3>Berry Smoothie Bowl</h3>
                <p>Blended berries with granola and fresh fruits</p>
                <span class="price">$9.99</span>
                <button>Add to Cart</button>
            </div> -->
        </div>

        <a href="products.php" class="view-all">View All Products</a>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
