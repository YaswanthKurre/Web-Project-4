<?php
include_once 'dbconnection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

// Function to get user contact information
function getUserContactInfo($username, $preferredContactMethod)
{
    global $conn; // Assuming $conn is your database connection variable

    $contactData = array();

    // Fetch email and phone number based on the preferred contact method
    $sql = "SELECT email, phonenumber FROM user_details WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contactData[] = array(
                    'email' => $row['email'],
                    'phonenumber' => $row['phonenumber']
                );
            }
        } else {
            // No results found
            return "No contact information available";
        }
    } else {
        // Error executing the SQL statement
        return "Error: " . $stmt->error;
    }

    $stmt->close();

    // Format the contact data based on the preferred contact method
    foreach ($contactData as $contact) {
        if ($preferredContactMethod == 'email') {
            return $contact['email'];
        } elseif ($preferredContactMethod == 'phone') {
            return $contact['phonenumber'];
        } else {
            return "Invalid preferred contact method";
        }
    }
}

// Initialize an empty array to store the conditions
$conditions = array();

// Process search criteria
$location = isset($_GET['location']) ? sanitizeInput($_GET['location']) : '';
$min_price = isset($_GET['min_price']) ? intval($_GET['min_price']) : null;
$max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : null;
$property_type = isset($_GET['property_type']) ? sanitizeInput($_GET['property_type']) : '';
$min_square_foot = isset($_GET['min_square_foot']) ? intval($_GET['min_square_foot']) : null;
$max_square_foot = isset($_GET['max_square_foot']) ? intval($_GET['max_square_foot']) : null;

// Build conditions based on provided filters
if (!empty($location)) {
    $conditions[] = "city LIKE '%$location%'";
}
if ($min_price !== null) {
    $conditions[] = "asking_price >= $min_price";
}
if ($max_price !== null) {
    $conditions[] = "asking_price <= $max_price";
}
if (!empty($property_type)) {
    if ($property_type !== 'any')
        $conditions[] = "property_type = '$property_type'";
}
if ($min_square_foot !== null) {
    $conditions[] = "square_footage >= $min_square_foot";
}
if ($max_square_foot !== null) {
    $conditions[] = "square_footage <= $max_square_foot";
}

// Construct the WHERE clause if conditions are present
$whereClause = '';
if (!empty($conditions)) {
    $whereClause = 'WHERE ' . implode(' AND ', $conditions);
}

$sql = "SELECT * FROM user_wantsto_sell $whereClause order by asking_price desc";
$result = $conn->query($sql);

$properties = array();
if (!$result) {
    echo "Error: " . $conn->error; // Display any SQL errors
} else {
    $properties = $result->fetch_all(MYSQLI_ASSOC);
}

// Close the database connection
// $conn->close();
?>

<hr>
<h1>Search Results</h1>
<hr>
<?php if (empty($properties)) : ?>
    <p>No properties found matching the search criteria.</p>
<?php else :?>
    <div class="dashboard-container">
        <?php foreach ($properties as $property) : ?>
            <div class="property-record">
                <?php
                if (!empty($property['image_path'])) {
                    echo '<img src="' . $property['image_path'] . '" alt="' . $property['property_name'] . '">';
                } else {
                    // Display a default/random image if no image in the database
                    echo '<img src="default_home.jpg" alt="Default Image">';
                }
                echo '<h2>' . $property['property_name'] .'</h2>';
                echo  '<h3> Posted by: <span style="color:red;">'. $property['username'].'</span></h3>';
                echo '<p style="color:blue;"> Contact Info: ' . getUserContactInfo($property['username'], $property['preferred_contact_method']) . '</p>';
                echo '<p>' . $property['property_description'] . '</p>';
                echo '<p>' . $property['bedrooms'] . ' Bed ' . $property['bathrooms'] . ' Bath' . '</p>';
                echo '<p>' . $property['square_footage'] . ' Sq.Ft, ' . $property['year_built'] . ' Built' . '</p>';
                echo '<p>Asking Price: $' . $property['asking_price'] . ' +7% tax</p>';
                ?>
            </div>
        <?php endforeach;
    endif; ?>
</div>
