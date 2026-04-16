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
    <title>Register - NeoKart</title>
    <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>
    <?php include '../nav.php'; ?>

    <div class="login-container">
        <h1>Create Account</h1>
        <p class="subtitle">Sign up to start ordering </p>
        
        <?php if (isset($_SESSION['register_error'])) { ?>
            <div style="background-color: #fee; color: #e41313; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fcc; font-size: 14px; text-align: center;">
                <?php echo $_SESSION['register_error']; ?>
            </div>
            <?php unset($_SESSION['register_error']); ?>
        <?php } ?>
        
        <form action="register.php" method="POST">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" placeholder="John Doe" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" placeholder="+1234567890" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="john@example.com" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="........" required>
            
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="........" required>
            
            <button type="submit" class="login-btn">Create Account</button>
        </form>
        
        <p class="register-link">Already have an account? <a href="create_login.php">Login here</a></p>
    </div>
</body>
</html>
