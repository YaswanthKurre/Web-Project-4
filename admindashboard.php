<?php
include("header.php");
include_once 'dbconnection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SESSION['username']!=='admin') {
    header('Location: index.php');
    exit();
}

function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

// Function to get total number of users
function getTotalUsers()
{
    global $conn;
    $query = "SELECT COUNT(*) as totalUsers FROM user_details";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['totalUsers'];
    } else {
        return "Error: " . $conn->error;
    }
}

// Function to get number of users registered today
function getUsersRegisteredToday(){
    global $conn;
    $today = date("Y-m-d");
    $query = "SELECT COUNT(*) as usersToday FROM user_details WHERE DATE(registration_date) = '$today'";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['usersToday'];
    } else {
        return "Error: " . $conn->error;
    }
}

function getTypeCount($type){
    global $conn;
    $sql = "SELECT COUNT(*) as type_count FROM user_wantsto_sell WHERE property_type='".$type."'";
    $result = $conn->query($sql);

    if (!$result) {
        echo "Error: " . $conn->error; 
    } else {
        $propertyCounts = $result->fetch_assoc();
        return $propertyCounts['type_count'];
    }
}

function getPropertyData()
{
    global $conn;

    $properties = array();

    $query = "SELECT id,property_name, property_type, asking_price, bedrooms, bathrooms, square_footage, year_built, city, preferred_closing_date,username FROM user_wantsto_sell order by preferred_closing_date";
    $result = $conn->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $properties[] = $row;
        }
    } else {
        echo "Error: " . $conn->error;
    }

    return $properties;
}

$properties = getPropertyData();
$totalUsers = getTotalUsers();
$usersToday = getUsersRegisteredToday();
?>



<div class="dashboard-admin">
    <div class="tile">
        <h2>Total Users</h2>
        <p style="font-size:44px; color:green;"><?php echo $totalUsers; ?></p>
    </div>
    <div class="tile">
        <h2>Users Registered Today</h2>
        <p style="font-size:44px; color:green;"><?php echo $usersToday; ?></p>
    </div>
    <div class="tile">
        <h2>Flagged Users</h2>
        <p style="font-size:44px; color:green;"><?php echo 'N/A'; ?></p>
    </div>
</div>
<br/>
<hr>
<div class="dashboard-admin">
    <div class="tile">
        <h2>Apartments</h2>
        <p style="font-size:44px; color:green;"><?php echo getTypeCount('apartment'); ?></p>
    </div>
    <div class="tile">
        <h2>Houses</h2>
        <p style="font-size:44px; color:green;"><?php echo getTypeCount('house'); ?></p>
    </div>
    <div class="tile">
        <h2>Lands</h2>
        <p style="font-size:44px; color:green;"><?php echo getTypeCount('land'); ?></p>
    </div>
</div>

<br/>
<hr>
<br/>
<?php
echo '<table id="data">';
echo '<tr>';
echo '<th>Property Name</th>';
echo '<th>Type</th>';
echo '<th>Asking Price</th>';
echo '<th>Configuration</th>';
echo '<th>Size</th>';
echo '<th>Year Built</th>';
echo '<th>City</th>';
echo '<th>Preferred Closing Date</th>';
echo '<th> Created by</th>';
echo '</tr>';

foreach ($properties as $property) {
    echo '<tr>';
    echo '<td>' . '<a href="add_sell_info.php?id=' . $property['id'] . '">'.$property['property_name'] . '</a></td>';
    echo '<td>' . $property['property_type'] . '</td>';
    echo '<td>$' . number_format($property['asking_price']) . '</td>';
    echo '<td>' . $property['bedrooms'] . ' Bed, ' . $property['bathrooms'] . ' Bath</td>';
    echo '<td>' . $property['square_footage'] . ' sq.ft</td>';
    echo '<td>' . $property['year_built'] . '</td>';
    echo '<td>' . $property['city'] . '</td>';
    echo '<td>' . $property['preferred_closing_date'] . '</td>';
    echo '<td>' . '<a href="profile.php?id=' . $property['username'] . '">'.$property['username'] . '</a></td>';
    echo '</tr>';
}

echo '</table>';
?>

</body>
</html>

