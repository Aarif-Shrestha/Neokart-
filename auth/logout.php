<?php
session_start();
session_unset(); // this is used to unset all the session variables
session_destroy(); // this is used to destroy the session
header("Location: ../index.php"); // this is used to redirect the user to the index page
exit();
?>
