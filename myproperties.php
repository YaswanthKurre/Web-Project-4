<?php
include("header.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
} else {
    header('Location: login.php');
    exit();
}

if(isset($_SESSION['profile']) && $_SESSION['profile'] == true){
}
else{
    header('Location: profile.php');
    exit();
}

include_once 'dbconnection.php';

// Function to sanitize input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
if ($username !== null) {
    $sql = "SELECT * FROM user_wantsto_sell WHERE username = ? order by asking_price desc";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $properties = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Username not found in the session.";
}

?>

<h1>My Properties for Sale</h1>
<hr>
<div id="dashboard">
    <?php
    echo '<div class="property-card">';
    echo '<a href="add_sell_info.php"><img src="plus.jpg" alt="Add New"></a>';
    echo '<h2><center>Add New Property</center></h2>';
    echo '</div>';
    foreach ($properties as $property) {
        echo '<div class="property-card">';
        if (!empty($property['image_path'])) {
            echo '<img src="' . $property['image_path'] . '" alt="' . $property['property_name'] . '">';
        } else {
        // Display a default/random image if no image in the database
            echo '<img src="default_home.jpg" alt="Default Image">';
        }
        echo '<h2>' . $property['property_name'] . '</h2>';
        echo '<p>' . $property['property_description'] . '</p>';
        echo '<p>' . $property['bedrooms'] . ' Bed ' . $property['bathrooms'] . ' Bath' . '</p>';
        echo '<p>' . $property['square_footage'] . ' Sq.Ft, ' . $property['year_built'] . ' Built' . '</p>';
        echo '<p>$' . $property['asking_price'] . '</p>';
        echo '<a href="add_sell_info.php?id=' . $property['id'] . '">Edit Property</a>';
        echo '</div>';
    }
    ?>
</div>

<?php include("footer.html"); ?>
