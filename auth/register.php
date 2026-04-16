<?php
    session_start();
    include "../config/db_config.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $phone = $_POST['phone'];
        

        if ($password != $confirm_password){
            $_SESSION['register_error'] = "Passwords do not match. Please try again.";
            header("Location: create_register.php");
            exit();
        } else {
            // Check if email already exists
            $check_query = "SELECT email FROM users WHERE email = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $_SESSION['register_error'] = "Email already registered. Please login.";
                header("Location: create_register.php");
                exit();
            }
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = 'INSERT INTO users (name,email,password,phone) VALUES (?,?,?,?)';
            $sql = $conn->prepare($query);
            $sql->bind_param("ssss",$fullname,$email,$hashed_password,$phone);
            $sql->execute();
            
            $_SESSION['register_success'] = "Account created successfully! Please login.";
            header("Location: create_login.php");
            exit();
        }
    }
?>
