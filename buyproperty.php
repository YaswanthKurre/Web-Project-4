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
?>


<h1>Property Search</h1>
<div id="filters">
    <div class="form-row">
        <label id="location-label" for="location">Location (City):</label>
        <input type="text" id="location" placeholder="Enter location">
    </div>

    <div class="form-row">
        <label id="min_price-label" for="min_price">Minimum Price:</label>
        <input type="number" id="min_price" placeholder="Min Price" min="0">
    </div>

    <div class="form-row">
        <label id="max_price-label" for="max_price">Maximum Price:</label>
        <input type="number" id="max_price" placeholder="Max Price" min="0">
    </div>

    <div class="form-row">
        <label id="property_type-label" for="property_type">Property Type:</label>
        <select id="property_type">
            <option value="">Any</option>
            <option value="house">House</option>
            <option value="apartment">Apartment</option>
            <option value="land">Land</option>
        </select>
    </div>

    <div class="form-row">
        <label id="min_square_foot-label" for="min_square_foot">Minimum Square Footage:</label>
        <input type="number" id="min_square_foot" placeholder="Min Sq. Foot" min="0">
    </div>

    <div class="form-row">
        <label id="max_square_foot-label" for="max_square_foot">Maximum Square Footage:</label>
        <input type="number" id="max_square_foot" placeholder="Max Sq. Foot" min="0">
    </div>

    <div class="form-row">
        <button id="search-button" onclick="filterProperties()">Search</button>
    </div>
</div>


<div id="propertyResults">
    <!-- Display filtered properties here -->
</div>

<script>
    function filterProperties() {
        // Retrieve filter values
        var location = document.getElementById('location').value;
        var minPrice = document.getElementById('min_price').value;
        var maxPrice = document.getElementById('max_price').value;
        var propertyType = document.getElementById('property_type').value;
        var minSquareFoot = document.getElementById('min_square_foot').value;
        var maxSquareFoot = document.getElementById('max_square_foot').value;

        // Create an object to store the parameters
        var params = {};

        // Add parameters only if they are filled out
        if (location.trim() !== '') {
            params['location'] = location;
        }
        if (minPrice.trim() !== '') {
            params['min_price'] = minPrice;
        }
        if (maxPrice.trim() !== '') {
            params['max_price'] = maxPrice;
        }
        if (propertyType.trim() !== '') {
            params['property_type'] = propertyType;
        }
        if (minSquareFoot.trim() !== '') {
            params['min_square_foot'] = minSquareFoot;
        }
        if (maxSquareFoot.trim() !== '') {
            params['max_square_foot'] = maxSquareFoot;
        }

        // Construct the URL for the PHP script
        var url = 'search_results.php?' + new URLSearchParams(params).toString();

        // Fetch the filtered results using AJAX
        fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById('propertyResults').innerHTML = data;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
    }
</script>
</body>
</html>

<?php include("footer.html"); ?>
