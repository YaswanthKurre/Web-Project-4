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
    $sql = "SELECT * FROM user_wantsto_buy WHERE id = ?";
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
<form action="buyinfo_submit.php" method="post">
    <h1>Buyer Preferences</h1>
    <h2> Welcome <?php echo $_SESSION["username"]?></h2>

    <?php if ($isUpdateMode): ?>
        <input type="hidden" name="property_id" value="<?php echo $propertyId; ?>">
    <?php endif; ?>

    <label for="name">Preferred Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['name']) : ''; ?>"><br>


    <label for="preferred_location">Preferred Location:</label>
    <input type="text" id="preferred_location" name="preferred_location" value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['preferred_location']) : ''; ?>"><br>

    <label for="budget_range">Budget Range:</label>
    <input type="number" id="budget_range" name="budget_range" step="0.01" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['budget_range']) : ''; ?>"><br>

    <!-- Property Type -->
    <label for="property_type">Property Type:</label>
    <select id="property_type" name="property_type" required>
        <option value="apartment" <?php echo ($isUpdateMode && $propertyDetails['property_type'] === 'apartment') ? 'selected' : ''; ?>>Apartment</option>
        <option value="house" <?php echo ($isUpdateMode && $propertyDetails['property_type'] === 'house') ? 'selected' : ''; ?>>House</option>
        <option value="land" <?php echo ($isUpdateMode && $propertyDetails['property_type'] === 'land') ? 'selected' : ''; ?>>Land</option>
    </select><br>


    <!-- Number of Bedrooms -->
    <label for="bedrooms">Number of Bedrooms:</label>
    <input type="number" id="bedrooms" name="bedrooms" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['bedrooms']) : ''; ?>"><br>

    <!-- Number of Bathrooms -->
    <label for="bathrooms">Number of Bathrooms:</label>
    <input type="number" id="bathrooms" name="bathrooms" required value="<?php echo $isUpdateMode ? sanitizeInput($propertyDetails['bathrooms']) : ''; ?>"><br>

    <!-- Submit Button -->
    <input type="submit" value="<?php echo $isUpdateMode ? 'Update' : 'Submit'; ?>">
</form>
<?php include("footer.html"); ?>