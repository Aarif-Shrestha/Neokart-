<?php
    session_start();
?>
<?php

    include "config/db_config.php";

    $category = '';
    if (isset($_GET['category'])) {
        $category = $_GET['category'];
    }

    if (isset($_GET['searchInput'])) {
        $search = $_GET['searchInput'];

        $query = "SELECT * FROM products WHERE (name LIKE '%$search%' OR description LIKE '%$search%')";

        if ($category != '') {
            $query .= " AND category_id = '$category'";
        }

        $sql = $conn->query($query);
    } 
    else {
        $query = "SELECT * FROM products";

        if ($category != '') {
            $query .= " WHERE category_id = '$category'";
        }

        $sql = $conn->query($query);
    }

    $query1 = "SELECT * FROM category";
    $sql1 = $conn->query($query1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Products - NeoKart</title>
</head>
<body>

    <?php $active_page = 'products'; ?>
    <?php include 'nav.php'; ?>

    <div class="products-container">

        <div class="products-header">
            <h1>Our Products</h1>
            <p class="products-subtitle">Browse our collection of premium items</p>
        </div>

        <div class="products-controls">

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search products...">
            </div>

            <div class="category-filters">
                <a href="products.php">
                    <button class="filter-btn <?php echo ($category == '') ? 'active' : ''; ?>">
                        All
                    </button>
                </a>

                <?php while ($row1 = $sql1->fetch_assoc()) { ?>
                    <a href="products.php?category=<?php echo $row1['category_id']; ?>">
                        <button class="filter-btn <?php echo ($category == $row1['category_id']) ? 'active' : ''; ?>">
                            <?php echo $row1['category_name']; ?>
                        </button>
                    </a>
                <?php } ?>
            </div>

        </div>

        <div class="product-grid">
            <?php while ($row = $sql->fetch_assoc()) { ?>
                <div class="card">
                    <img src="new_img/<?php echo $row['image']; ?>" alt="">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <span class="price">Rs <?php echo $row['price']; ?></span>
                    <a href="<?php echo isset($_SESSION['user']) ? 'cart.php?product_id=' . $row['product_id'] : './auth/create_login.php'; ?>"><button>Add to Cart</button></a>
                </div>
            <?php } ?>
        </div>

    </div>

    <script>
        const search = document.getElementById('searchInput');
        const cards = document.querySelectorAll('.card');

        search.addEventListener('input', function () {
            const term = this.value.toLowerCase();

            cards.forEach(card => {
                const text =
                    card.querySelector('h3').textContent.toLowerCase() +
                    card.querySelector('p').textContent.toLowerCase();

                card.style.display = text.includes(term) ? 'block' : 'none';
            });
        });
    </script>

    <?php include 'footer.php'; ?>

</body>
</html>
