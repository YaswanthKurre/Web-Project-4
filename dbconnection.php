<?php

$servername = "localhost";
$username = "kgumpena1";
$password = "kgumpena1";
$dbname = "kgumpena1";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
