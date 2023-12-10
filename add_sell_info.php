<?php
include("header.php");

include_once 'dbconnection.php';

// Function to sanitize input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

// Check for "id" parameter in the URL
$propertyId = isset($_GET['id']) ? $_GET['id'] : null;

// Check if it's an update mode
$isUpdateMode = !empty($propertyId);

// Fetch property details if in update mode
if ($isUpdateMode) {
    // Fetch property details using $propertyId
    $sql = "SELECT * FROM user_wantsto_sell WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $propertyId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $propertyDetails = $result->fetch_assoc();
    } else {
        echo "Property not found.";
        exit;
    }

    $stmt->close();
}
?>


<form id="sellinfo_submit" action="sellinfo_submit.php" method="post" enctype="multipart/form-data">
    <h1>Property Information Form</h1>
    <h2> Welcome <?php echo $_SESSION["username"]?></h2>

    <?php if ($isUpdateMode): ?>
        <input type="hidden" name="property_id" value="<?php echo $propertyId; ?>">
    <?php endif; ?>

    <label for="property_name">Property Name:</label>
    <input type="text" id="property_name" name="property_name" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['property_name']) : ''; ?>"><br>

    <label for="property_type">Property Type:</label>
    <select id="property_type" name="property_type" required>
        <option value="apartment" <?php echo ($isUpdateMode && $propertyDetails['property_type'] === 'apartment') ? 'selected' : ''; ?>>Apartment</option>
        <option value="house" <?php echo ($isUpdateMode && $propertyDetails['property_type'] === 'house') ? 'selected' : ''; ?>>House</option>
        <option value="land" <?php echo ($isUpdateMode && $propertyDetails['property_type'] === 'land') ? 'selected' : ''; ?>>Land</option>
    </select><br>

    <label for="bedrooms">Number of Bedrooms:</label>
    <input type="number" id="bedrooms" name="bedrooms" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['bedrooms']) : ''; ?>"><br>

    <label for="bathrooms">Number of Bathrooms:</label>
    <input type="number" id="bathrooms" name="bathrooms" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['bathrooms']) : ''; ?>"><br>

    <label for="square_footage">Square Footage:</label>
    <input type="number" id="square_footage" name="square_footage" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['square_footage']) : ''; ?>"><br>

    <label for="year_built">Year Built:</label>
    <input type="number" id="year_built" name="year_built" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['year_built']) : ''; ?>"><br>

    <!-- Property Features -->
    <label for="property_features">Property Features:</label>
    <input type="text" id="property_features" name="property_features" value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['property_features']) : ''; ?>"><br>

    <!-- Property Description -->
    <label for="property_description">Property Description:</label>
    <textarea id="property_description" name="property_description" rows="4"><?php echo $isUpdateMode ? sanitizeInput($propertyDetails['property_description']) : ''; ?></textarea><br>

    <!-- Location Details -->
    <label for="property_address">Property Address:</label>
    <input type="text" id="property_address" name="property_address" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['property_address']) : ''; ?>"><br>

    <label for="city">City:</label>
    <input type="text" id="city" name="city" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['city']) : ''; ?>"><br>

    <label for="state_province">State/Province:</label>
    <input type="text" id="state_province" name="state_province" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['state_province']) : ''; ?>"><br>

    <label for="zip_code">Zip Code:</label>
    <input type="text" id="zip_code" name="zip_code" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['zip_code']) : ''; ?>"><br>

    <!-- Pricing and Financial Information -->
    <label for="asking_price">Asking Price:</label>
    <input type="number" id="asking_price" name="asking_price" step="0.01" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['asking_price']) : ''; ?>"><br>

    <!-- Additional Information -->
    <label for="negotiation_terms">Negotiation Terms:</label>
    <textarea id="negotiation_terms" name="negotiation_terms" rows="3"><?php echo $isUpdateMode ? sanitizeInput($propertyDetails['negotiation_terms']) : ''; ?></textarea><br>

    <!-- Property Documents -->
    <label for="title_deeds">Title Deeds:</label>
    <input type="checkbox" id="title_deeds" name="title_deeds" <?php echo ($isUpdateMode && $propertyDetails['title_deeds'] == 1) ? 'checked' : ''; ?>><br>

    <label for="survey_reports">Survey Reports:</label>
    <input type="checkbox" id="survey_reports" name="survey_reports" <?php echo ($isUpdateMode && $propertyDetails['survey_reports'] == 1) ? 'checked' : ''; ?>><br>

    <label for="home_inspection_reports">Home Inspection Reports:</label>
    <input type="checkbox" id="home_inspection_reports" name="home_inspection_reports" <?php echo ($isUpdateMode && $propertyDetails['home_inspection_reports'] == 1) ? 'checked' : ''; ?>><br>

    <!-- Contact Information -->
    <label for="preferred_contact_method">Preferred Contact Method:</label>
    <select id="preferred_contact_method" name="preferred_contact_method" required>
        <option value="phone" <?php echo ($isUpdateMode && $propertyDetails['preferred_contact_method'] === 'phone') ? 'selected' : ''; ?>>Phone</option>
        <option value="email" <?php echo ($isUpdateMode && $propertyDetails['preferred_contact_method'] === 'email') ? 'selected' : ''; ?>>Email</option>
    </select><br>

    <!-- Listing Preferences -->
    <label for="listing_duration">Listing Duration (days):</label>
    <input type="number" id="listing_duration" name="listing_duration" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['listing_duration']) : ''; ?>"><br>

    <label for="listing_platforms">Listing Platforms:</label>
    <textarea id="listing_platforms" name="listing_platforms" rows="3"><?php echo $isUpdateMode ? sanitizeInput($propertyDetails['listing_platforms']) : ''; ?></textarea><br>

    <!-- Preferred Closing Date -->
    <label for="preferred_closing_date">Preferred Closing Date:</label>
    <input type="date" id="preferred_closing_date" name="preferred_closing_date" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['preferred_closing_date']) : ''; ?>"><br>

    <!-- Terms and Conditions -->
    <label for="terms_and_conditions">Terms and Conditions:</label>
    <textarea id="terms_and_conditions" name="terms_and_conditions" rows="4"><?php echo $isUpdateMode ? sanitizeInput($propertyDetails['terms_and_conditions']) : ''; ?></textarea><br>

    <!-- Property Image Upload -->
    <label for="property_image">Property Image:</label>
    <input type="file" id="property_image" name="property_image" accept="image/*"><br>
    <input type="submit" value="<?php echo $isUpdateMode ? 'Update' : 'Submit'; ?>">
</form>
<?php include("footer.html"); ?>
