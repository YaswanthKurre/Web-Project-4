<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password1"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $loginQuery = "INSERT INTO login_details (username, password) VALUES ('$username', '$hashedPassword')";

    if ($conn->query($loginQuery) === TRUE) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        header("Location: profile.php");
        exit();
    } 
    else {
        $_SESSION["error"] = "Error creating user details: " . $conn->error;
        header("Location: signup.php");
        exit();
    }
    $conn->close();
} else {
    header("Location: signup.php");
    exit();
}
?>
