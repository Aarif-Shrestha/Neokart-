<?php
// Determine the base path based on the current directory
$base_path = (basename(dirname($_SERVER['PHP_SELF'])) == 'auth') ? '../' : '';
?>

<nav class="navbar">
    <div class="logo">
        <img src="<?php echo $base_path; ?>assets/logo.png" alt="">
        <span class="logo-text">NeoKart</span>
    </div>

    <div class="navbar-center">
        <ul class="nav-links">
            <li><a href="<?php echo $base_path; ?>index.php" class="<?php echo (isset($active_page) && $active_page == 'home') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="<?php echo $base_path; ?>products.php" class="<?php echo (isset($active_page) && $active_page == 'products') ? 'active' : ''; ?>">Products</a></li>
        </ul>
    </div>

    <div class="navbar-right">
        <a href="<?php echo $base_path; ?>cart.php" class="cart-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>  
        </a>

        <?php if (isset($_SESSION['user'])): ?>
            <a href="<?php echo $base_path; ?>auth/logout.php" class="login-btn">Logout</a>
        <?php else: ?>
            <a href="<?php echo $base_path; ?>auth/create_login.php" class="login-btn">Login</a>
        <?php endif; ?>

    </div>
</nav>
