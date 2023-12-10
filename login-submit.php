<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once 'dbconnection.php';
$_SESSION["error"] = "";

$enteredUsername = $_POST['username'];
$enteredPassword = $_POST['password'];

function sanitizeInput($input) {
    global $conn;
    return mysqli_real_escape_string($conn, $input);
}

function verifyLogin($username, $password) {
    global $conn;
    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    $query = "SELECT password FROM login_details WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];
            if (password_verify($password, $hashedPassword)) {
                return true;
            }
        }
    } else {
        die("Query failed: " . $conn->error);
    }
    return false;
}

function checkUserFilleddata($username){
    global $conn;
    $username = sanitizeInput($username);
    $query = "SELECT username FROM user_details WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            return true;
        }
    } else {
        die("Query failed: " . $conn->error);
    }
    return false;
}

function getRedirectRule($username){
    global $conn;
    $username = sanitizeInput($username);
    $query = "SELECT type FROM user_details WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_type = $row['type'];
            return $user_type;
        }
    } else {
        die("Query failed: " . $conn->error);
    }
    return false;
}

if (verifyLogin($enteredUsername, $enteredPassword)) {
    $_SESSION["loggedin"] = true;
    $_SESSION["profile"]=true;
    $_SESSION["username"] = $enteredUsername;
    if(!checkUserFilleddata($enteredUsername)){
        $_SESSION["profile"]=false;
    }
    $redirect=getRedirectRule($enteredUsername);
    if($redirect=="buy"){
        $_SESSION["user_type"]="buy";
        header('Location: buyproperty.php');
        exit();
    }
    else if($redirect=="sell"){
        $_SESSION["user_type"]="sell";
        header('Location: myproperties.php');
        exit();
    }
    else{
        $_SESSION["user_type"]="both";
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION["error"] = "Incorrect Username or Password";
    header('Location: login.php');
    exit();
}
$conn->close();
?>
