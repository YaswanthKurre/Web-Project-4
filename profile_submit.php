<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once 'dbconnection.php';

function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST["username"]);
    $firstname = sanitizeInput($_POST["firstname"]);
    $lastname = sanitizeInput($_POST["lastname"]);
    $email = sanitizeInput($_POST["email"]);
    $phonenumber = sanitizeInput($_POST["phonenumber"]);
    $street_address = sanitizeInput($_POST["street_address"]);
    $city = sanitizeInput($_POST["city"]);
    $state = sanitizeInput($_POST["state"]);
    $zipcode = sanitizeInput($_POST["zipcode"]);
    $employment_information = sanitizeInput($_POST["employment_information"]);
    $social_media_profiles = sanitizeInput($_POST["social_media_profiles"]);
    $how_did_you_hear_about_us = sanitizeInput($_POST["how_did_you_hear_about_us"]);
    $terms_and_conditions_accepted = isset($_POST["terms_and_conditions_accepted"]) ? 1 : 0;
    $type = sanitizeInput($_POST["type"]);

    // Prepare and execute SQL statement
    $sql = "INSERT INTO user_details 
    (username, firstname, lastname, email, phonenumber, streetaddress, city, state, zipcode, employment_information, social_media_profiles, how_did_you_hear_about_us, terms_and_conditions_accepted, type, registration_date) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
    firstname = VALUES(firstname),
    lastname = VALUES(lastname),
    email = VALUES(email),
    phonenumber = VALUES(phonenumber),
    streetaddress = VALUES(streetaddress),
    city = VALUES(city),
    state = VALUES(state),
    zipcode = VALUES(zipcode),
    employment_information = VALUES(employment_information),
    social_media_profiles = VALUES(social_media_profiles),
    how_did_you_hear_about_us = VALUES(how_did_you_hear_about_us),
    terms_and_conditions_accepted = VALUES(terms_and_conditions_accepted),
    type = VALUES(type)";

    $today = date('Y-m-d');

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssiss", $username, $firstname, $lastname, $email, $phonenumber, $street_address, $city, $state, $zipcode, $employment_information, $social_media_profiles, $how_did_you_hear_about_us, $terms_and_conditions_accepted, $type, $today);

    if ($stmt->execute()) {
        $_SESSION["loggedin"] = true;
        $_SESSION["profile"]=true;
        if($type=="buy"){
            $_SESSION["user_type"]="buy";
        }
        else if($type=="sell"){
            $_SESSION["user_type"]="sell";
        }
        else{
            $_SESSION["user_type"]="both";
        }
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
