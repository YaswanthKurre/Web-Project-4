<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $query = "SELECT * FROM login_details WHERE username = '$username'";
    
    // Execute the query
    $result = $conn->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            echo "exists";
        } else {
            echo "available";
        }
    } else {
        echo "error";
    }
}
?>
