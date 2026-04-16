<?php
    session_start();


    include "../config/db_config.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $email = $_POST["login_mail"];
        $password = $_POST["login_password"];

        // var_dump($email, $password);
    


        $query = "SELECT user_id,name,email,phone,password,is_admin  FROM users WHERE email = ?";   // thi actually checks if the email and the pass3ord macthc iny rhe database   
        $sql = $conn->prepare($query);
        $sql->bind_param("s",$email);       // this sends the email and password inserted into the sql query 
        $sql->execute();
        $result = $sql->get_result();
        echo var_dump($result);

        if ($result -> num_rows === 1){
            $record = $result->fetch_assoc(); // this fetches the record from the database
            $db_user = $record["email"];
            $db_password = $record["password"];

            if (password_verify($password, $db_password)){
                $_SESSION["user"] = $db_user;
                $_SESSION["id"] = $record['user_id'];
                $_SESSION["is_admin"] = $record['is_admin'];

                if ($_SESSION["is_admin"]){
                    header("Location: ../admin/admin.php");
                    exit();
                }
                else{
                header("Location: ../index.php"); // this redirects the user to the index page after successful login
                }   
            }             

            else{
                $_SESSION['login_error'] = "Incorrect password. Please try again.";
                header("Location: create_login.php");
                exit();
            }
        }
         else {
            $_SESSION['login_error'] = "Email not found. Please register first.";
            header("Location: create_login.php");
            exit();
        }

    }

?>

