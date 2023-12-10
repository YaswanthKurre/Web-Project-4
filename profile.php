<?php
include("header.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'dbconnection.php';

// Function to sanitize input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

// Check for "id" parameter in the URL
$userId = isset($_GET['id']) ? $_GET['id'] : null;

// Check if it's an update mode
$isUpdateMode = !empty($userId);

// Fetch property details if in update mode
if ($isUpdateMode) {
    // Fetch property details using $propertyId
    $sql = "SELECT * FROM user_details WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userDetails = $result->fetch_assoc();
    } else {
        $isUpdateMode=false;
    }

    $stmt->close();
}
?>

<form action="profile_submit.php" method="post">
    <h1>User Details Form</h1>
    <h2> Welcome <?php echo $_SESSION["username"]?></h2>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['username']) : $_SESSION["username"] ?>" readonly><br>

    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['firstname']) : '' ?>" required><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['lastname']) : '' ?>"><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['email']) : '' ?>"><br>

    <label for="phonenumber">Phone Number:</label>
    <input type="tel" id="phonenumber" name="phonenumber" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['phonenumber']) : '' ?>"><br>

    <label for="street_address">Street Address:</label>
    <input type="text" id="street_address" name="street_address" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['streetaddress']) : '' ?>"><br>

    <label for="city">City:</label>
    <input type="text" id="city" name="city" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['city']) : '' ?>"><br>

    <label for="state">State:</label>
    <input type="text" id="state" name="state" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['state']) : '' ?>"><br>

    <label for="zipcode">Zip Code:</label>
    <input type="text" id="zipcode" name="zipcode" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['zipcode']) : '' ?>"><br>

    <label for="type">I want to:</label>
    <select id="type" name="type" required>
        <option value="buy" <?php echo ($isUpdateMode && $userDetails['type'] === 'buy') ? 'selected' : ''; ?>>Buy</option>
        <option value="sell" <?php echo ($isUpdateMode && $userDetails['type'] === 'sell') ? 'selected' : ''; ?>>Sell</option>
        <option value="buy_sell" <?php echo ($isUpdateMode && $userDetails['type'] === 'buy_sell') ? 'selected' : ''; ?>>Buy & Sell</option>
    </select><br>

    <label for="employment_information">Employment Information:</label>
    <input type="text" id="employment_information" name="employment_information" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['employment_information']) : '' ?>"><br>

    <label for="social_media_profiles">Social Media Profiles:</label>
    <input type="text" id="social_media_profiles" name="social_media_profiles" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['social_media_profiles']) : '' ?>"><br>

    <label for="how_did_you_hear_about_us">How Did You Hear About Us:</label>
    <input type="text" id="how_did_you_hear_about_us" name="how_did_you_hear_about_us" value="<?php echo $isUpdateMode ? sanitizeInput($userDetails['how_did_you_hear_about_us']) : '' ?>"><br>

    <label for="terms_and_conditions_accepted">Terms and Conditions Accepted:</label>
    <input type="checkbox" id="terms_and_conditions_accepted" name="terms_and_conditions_accepted" <?php echo ($isUpdateMode && $userDetails['terms_and_conditions_accepted'] == 1) ? 'checked' : ''; ?> required>


    <button type="submit"><?php echo $isUpdateMode ? 'Update' : 'Submit'; ?></button>
</form>
<?php include("footer.html"); ?>