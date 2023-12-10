<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Include database connection logic
include_once 'dbconnection.php';

// Function to sanitize input
function sanitizeInput($input)
{
    return isset($input) ? htmlspecialchars(strip_tags(trim($input))) : '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = sanitizeInput($_SESSION["username"]);
    $property_name = sanitizeInput($_POST["property_name"]);
    $property_type = sanitizeInput($_POST["property_type"]);
    $bedrooms = sanitizeInput($_POST["bedrooms"]);
    $bathrooms = sanitizeInput($_POST["bathrooms"]);
    $square_footage = sanitizeInput($_POST["square_footage"]);
    $year_built = sanitizeInput($_POST["year_built"]);
    $property_features = sanitizeInput($_POST["property_features"]);
    $property_description = sanitizeInput($_POST["property_description"]);
    $property_address = sanitizeInput($_POST["property_address"]);
    $city = sanitizeInput($_POST["city"]);
    $state_province = sanitizeInput($_POST["state_province"]);
    $zip_code = sanitizeInput($_POST["zip_code"]);
    $asking_price = sanitizeInput($_POST["asking_price"]);
    $negotiation_terms = sanitizeInput($_POST["negotiation_terms"]);

    // Property Documents
    $title_deeds = isset($_POST["title_deeds"]) ? 1 : 0;
    $survey_reports = isset($_POST["survey_reports"]) ? 1 : 0;
    $home_inspection_reports = isset($_POST["home_inspection_reports"]) ? 1 : 0;

    // Contact Information
    $preferred_contact_method = sanitizeInput($_POST["preferred_contact_method"]);

    // Listing Preferences
    $listing_duration = sanitizeInput($_POST["listing_duration"]);
    $listing_platforms = sanitizeInput($_POST["listing_platforms"]);

    // Preferred Closing Date
    $preferred_closing_date = sanitizeInput($_POST["preferred_closing_date"]);

    // Terms and Conditions
    $terms_and_conditions = sanitizeInput($_POST["terms_and_conditions"]);

    if (isset($_FILES["property_image"]) && $_FILES["property_image"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/"; // Specify your upload directory
        $uploadFile = $uploadDir . basename($_FILES["property_image"]["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["property_image"]["tmp_name"], $uploadFile)) {
            // File upload was successful
            $property_image = $uploadFile;
        } else {
            // File upload failed
            echo "Error uploading property image.";
            exit;
        }
    } else {
        $property_image='';
    }
    // Check if it's an update mode
    $propertyId = isset($_POST['property_id']) ? $_POST['property_id'] : null;
    $isUpdateMode = !empty($propertyId);

    if ($isUpdateMode) {
        $updateSql = "UPDATE user_wantsto_sell SET property_name=?, property_type=?, bedrooms=?, bathrooms=?, square_footage=?, year_built=?,
        property_features=?, property_description=?, property_address=?, city=?, state_province=?, zip_code=?,
        asking_price=?, negotiation_terms=?, title_deeds=?, survey_reports=?, home_inspection_reports=?,
        preferred_contact_method=?, listing_duration=?, listing_platforms=?, preferred_closing_date=?, terms_and_conditions=?,
        image_path=? WHERE id=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssiiiisssssiisiiisisssss", $property_name, $property_type, $bedrooms, $bathrooms, $square_footage, $year_built,
          $property_features, $property_description, $property_address, $city, $state_province, $zip_code, $asking_price, $negotiation_terms, $title_deeds, $survey_reports, $home_inspection_reports, $preferred_contact_method,
          $listing_duration, $listing_platforms, $preferred_closing_date, $terms_and_conditions, $property_image, $propertyId);
        $updateStmt->execute();
        $rowsAffected = $updateStmt->affected_rows;
        if ($updateStmt->error) {
            echo "Error: " . $updateStmt->error;
        } else {
            header('Location: myproperties.php');
            exit();
        }

        $updateStmt->close();
    }
    else{

        $insertSql = "INSERT INTO user_wantsto_sell (username, property_name, property_type, bedrooms, bathrooms, square_footage, year_built, 
          property_features, property_description, property_address, city, state_province, zip_code, asking_price, 
          negotiation_terms, title_deeds, survey_reports, home_inspection_reports, preferred_contact_method, 
          listing_duration, listing_platforms, preferred_closing_date, terms_and_conditions, image_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("sssiiiisssssiisiiisissss", $username, $property_name, $property_type, $bedrooms, $bathrooms, $square_footage, $year_built,
            $property_features, $property_description, $property_address, $city, $state_province, $zip_code, $asking_price,
            $negotiation_terms, $title_deeds, $survey_reports, $home_inspection_reports, $preferred_contact_method,
            $listing_duration, $listing_platforms, $preferred_closing_date, $terms_and_conditions, $property_image);
        $insertStmt->execute();
        if ($insertStmt->error) {
            echo "Error: " . $insertStmt->error;
        } else {
            header('Location: myproperties.php');
            exit();
        }
        $insertStmt->close();

    }
    $conn->close();
}
?>
