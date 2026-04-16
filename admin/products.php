<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ./auth/create_login.php");
    exit();
}
?>




<?php
    include "../config/db_config.php";

    if($_SERVER['REQUEST_METHOD'] === "POST" && !isset($_POST['edit_id'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $image = $_FILES['image']['name'];
        $tmp = explode('.', $image);
        $new_image_name = round(microtime(true)) . '.' . end($tmp);
        $image_path = "../new_img/" . $new_image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);


        $query = "INSERT INTO products (name, price , description , image, category_id) VALUES (?,?,?,?,?)";
        $sql = $conn -> prepare($query);
        $sql -> bind_param("sdssi", $name, $price, $description, $new_image_name, $category);
        if ($sql->execute()) {
        echo "Product added successfully!";
        } else {
        echo "Error: " . $sql->error;
        }

    }

    if(isset($_POST['edit_id'])){
    $edit_id = $_POST['edit_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    if(!empty($_FILES['image']['name'])){
        $image = $_FILES['image']['name'];
        $tmp = explode('.', $image);
        $new_image_name = round(microtime(true)) . '.' . end($tmp);
        $image_path = "../new_img/" . $new_image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

        $updateQuery = "UPDATE products SET name=?, price=?, description=?, category_id=?, image=? WHERE product_id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sdsisi", $name, $price, $description, $category, $new_image_name, $edit_id);
    } else {
        $updateQuery = "UPDATE products SET name=?, price=?, description=?, category_id=? WHERE product_id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sdssi", $name, $price, $description, $category, $edit_id);
    }

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>
<?php

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $deleteQuery = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // Search logic
    if (isset($_GET['searchInput'])) {
        $search = $_GET['searchInput'];
        $query1 = "SELECT products.*, category.category_name FROM products 
                   JOIN category ON products.category_id = category.category_id 
                   WHERE (products.name LIKE '%$search%' OR products.description LIKE '%$search%')";
    } else {
        $query1 = "SELECT products.*, category.category_name FROM products 
                   JOIN category ON products.category_id = category.category_id";
    }
    
    $result = $conn->query($query1);
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products - Admin Panel</title>
<link rel="stylesheet" href="../style/admin.css">
</head>
<body>
    <nav>
        <h1>Admin Panel</h1>
        <a href="admin.php">📊 Dashboard</a>
        <a href="products.php" class="active">📦 Products</a>
        <a href="orders.php">🛍️ Orders</a>
        <a href="users.php">👥 Users</a>
    </nav>

    <section>
        <div class="top-header">
            <div class="sub-header">
                <h2>Products</h2>
                <p>Manage your product catalog</p>
            </div>
            <button onclick="openModal()">+ Add Product</button>
        </div>
        
        <form method="GET" action="products.php" style="margin-bottom: 20px; position: relative; max-width: 400px;">
            <input type="text" name="searchInput" placeholder="Search products..." value="<?php echo isset($_GET['searchInput']) ? htmlspecialchars($_GET['searchInput']) : ''; ?>" style="width: 100%; padding: 10px 40px 10px 10px; border: 1px solid #ddd; border-radius: 8px;">
            <?php if (isset($_GET['searchInput']) && $_GET['searchInput'] != '') { ?>
                <a href="products.php" style="position: absolute; right: 15px; top: 10px; color: #999; text-decoration: none; font-size: 18px; cursor: pointer; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;">✕</a>
            <?php } ?>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>PRODUCT</th>
                    <th>CATEGORY</th>
                    <th>PRICE</th>
                    <th>DESCRIPTION</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($row = $result->fetch_assoc()) {      // yeta start
                    $product_id = $row['product_id'];
                    $name = $row['name'];
                    $category_id = $row['category_id'];
                    $price = $row['price'];
                    $image = $row['image'];
                    $description = $row['description'];
                    $category = $row['category_name'];
                ?>
                <tr>
                    <td><?php echo "<img src='../new_img/$image' alt='$name' style='width:40px; height:40px; border-radius:8px; margin-right:12px; vertical-align:middle;'> $name"; ?></td>
                    <td><?php echo $category; ?></td>
                    <td><?php echo $price; ?></td>
                    <td><?php echo $description; ?></td>
                    <td class="actions">
                        <button class="btn-icon" onclick="openEditModal('<?php echo $product_id; ?>','<?php echo $name; ?>','<?php echo $price; ?>','<?php echo $description; ?>','<?php echo $category_id; ?>')">Edit</button>


                        <a href="products.php?id=<?php echo $product_id; ?>" onclick="return confirm('Are you sure you want to delete this product?')"><button class="btn-icon">Delete</button></a>   <!-- this is like anchor tag le chai back ma euta id pathauxa which we click ani tyo chai mathi isset garni xa vani then delete query -->
                    </td>
                </tr>
                <?php }      // so yesma chai like while loop vitra both php and html rakhna parda we need to do like two php tags to close the while loop yeta stop garyam ani 
                ?> 
            </tbody>
        </table>
    </section>

    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>

            <h2>Add Product</h2>

            <form class="my_form" method="POST" action="products.php" enctype="multipart/form-data">
                <label for="name">Product Name :</label>
                <input type="text" name="name" id="name" required><br>
                
                <label for="price">Price : </label>
                <input type="number" name="price" id="price" required><br>

                <label for="description">Description: </label><br>
                <textarea name="description" id="description" rows="4"></textarea><br>

                <label for="category">Category :</label>
                <select name="category" id="category">
                    <option value="">Select a category</option>
                    <?php
                        include "../config/db_config.php";

                        $query = "SELECT * FROM category";
                        $result = $conn->query($query);

                        if ($result -> num_rows > 0){
                            $record = $result->fetch_assoc(); 
                            while ($record){      # first row
                                $category_id = $record['category_id'];
                                $category_name = $record['category_name'];
                                $description = $record['description'];
                                echo "<option value='$category_id'>$category_name</option>";
                                $record = $result->fetch_assoc(); # changes row to second row
                            }
                        }
                    ?>
                </select><br>

                <label for="image">Image</label>
                <input type="file" name="image" id="image" required><br>

                <button type="submit">Save Product</button>
            </form>
        </div>
    </div>

    <div id="editProductModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Product</h2>
            <form class="my_form" method="POST" action="products.php" enctype="multipart/form-data">
                <input type="hidden" name="edit_id" id="edit_id"> <!-- to send product_id -->

                <label for="edit_name">Product Name :</label>
                <input type="text" name="name" id="edit_name" required><br>

                <label for="edit_price">Price :</label>
                <input type="number" name="price" id="edit_price" required><br>

                <label for="edit_description">Description :</label><br>
                <textarea name="description" id="edit_description" rows="4"></textarea><br>

                <label for="edit_category">Category :</label>
                <select name="category" id="edit_category">
                    <option value="">Select a category</option>
                    <?php
                        $query = "SELECT * FROM category";
                        $resultCat = $conn->query($query);
                        if ($resultCat->num_rows > 0){
                            $record = $resultCat->fetch_assoc(); 
                            while ($record){
                                $category_id = $record['category_id'];
                                $category_name = $record['category_name'];
                                echo "<option value='$category_id'>$category_name</option>";
                                $record = $resultCat->fetch_assoc();
                            }
                        }
                    ?>
                </select><br>

                <label for="edit_image">Image</label>
                <input type="file" name="image" id="edit_image"><br>

                <button type="submit">Update Product</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById("addProductModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("addProductModal").style.display = "none";
        }

        function openEditModal(id, name, price, description, category_id){
            document.getElementById("editProductModal").style.display = "flex";
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_name").value = name;
            document.getElementById("edit_price").value = price;
            document.getElementById("edit_description").value = description;
            document.getElementById("edit_category").value = category_id;
        }

        function closeEditModal(){
            document.getElementById("editProductModal").style.display = "none";
        }
    </script>





<!-- 
            <tbody>
                <tr>
                    <td>
                        <div class="product-cell">
                            <img src="https://via.placeholder.com/50" alt="Product">
                            <Strong class="product-name">Fresh Organic Salad</Strong>
                        </div>
                    </td>
                    <td>Salads</td>
                    <td>$12.99</td>
                    <td><span class="badge success">In Stock</span></td>
                    <td class="actions">
                        <button class="btn-icon">Edit</button>
                        <button class="btn-icon">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="product-cell">
                            <img src="https://via.placeholder.com/50" alt="Product">
                            <Strong class="product-name">Grilled Chicken Bowl</Strong>
                        </div>
                    </td>
                    <td>Bowls</td>
                    <td>$15.99</td>
                    <td><span class="badge success">In Stock</span></td>
                    <td class="actions">
                        <button class="btn-icon">Edit</button>
                        <button class="btn-icon">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="product-cell">
                            <img src="https://via.placeholder.com/50" alt="Product">
                            <Strong class="product-name">Margherita Pizza</Strong>
                        </div>
                    </td>
                    <td>Pizza</td>
                    <td>$18.99</td>
                    <td><span class="badge success">In Stock</span></td>
                    <td class="actions">
                        <button class="btn-icon">Edit</button>
                        <button class="btn-icon">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="product-cell">
                            <img src="https://via.placeholder.com/50" alt="Product">
                            <Strong class="product-name">Veggie Burger</Strong>
                        </div>
                    </td>
                    <td>Burgers</td>
                    <td>$13.99</td>
                    <td><span class="badge success">In Stock</span></td>
                    <td class="actions">
                        <button class="btn-icon">Edit</button>
                        <button class="btn-icon">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="product-cell">
                            <img src="https://via.placeholder.com/50" alt="Product">
                            <Strong class="product-name">Pasta Carbonara</Strong>
                        </div>
                    </td>
                    <td>Pasta</td>
                    <td>$16.99</td>
                    <td><span class="badge success">In Stock</span></td>
                    <td class="actions">
                        <button class="btn-icon">Edit</button>
                        <button class="btn-icon">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="product-cell">
                            <img src="https://via.placeholder.com/50" alt="Product">
                            <Strong class="product-name">Sushi Platter</Strong  >
                        </div>
                    </td>
                    <td>Sushi</td>
                    <td>$24.99</td>
                    <td><span class="badge success">In Stock</span></td>
                    <td class="actions">
                        <button class="btn-icon">Edit</button>
                        <button class="btn-icon">Delete</button>
                    </td>
                </tr>
            </tbody> -->