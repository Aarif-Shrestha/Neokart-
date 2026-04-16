<?php
    session_start();
     // this is used to start a session so that we can use session variables later on
    // if user is logged in, redirect to dashboard
    if (isset($_SESSION["user"])) {
        header("Location: ../index.php");
        exit();
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NeoKart</title>
    <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>

    <?php include '../nav.php'; ?>

    <div class="login-container">
        <h1>Welcome Back</h1>   
        <p>Login to your account to continue</p>
        
        <?php if (isset($_SESSION['register_success'])) { ?>
            <div style="background-color: #d4edda; color: #155724; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; font-size: 14px; text-align: center;">
                <?php echo $_SESSION['register_success']; ?>
            </div>
            <?php unset($_SESSION['register_success']); ?>
        <?php } ?>
        
        <?php if (isset($_SESSION['login_error'])) { ?>
            <div style="background-color: #fee; color: #e41313; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fcc; font-size: 14px; text-align: center;">
                <?php echo $_SESSION['login_error']; ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php } ?>
        
        <form action="login.php" method="POST">
            <label for="login_mail">Email</label>
            <input type="email" id="login_mail" name="login_mail" placeholder="john@example.com" required>
            
            <label for="login_password">Password</label>
            <input type="password" id="login_password" name="login_password" placeholder="........" required>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
        
        <p class="register-link">Don't have an account? <a href="create_register.php">Register here</a></p>
        

    </div>
</body>
</html>
