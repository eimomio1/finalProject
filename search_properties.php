<?php
// search_properties.php
// Database configuration
$servername = "localhost";
$username = "eimomio1"; // Replace with your database username
$password = "eimomio1"; // Replace with your database password
$dbname = "eimomio1";   // Replace with your database name

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the query parameter
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

// Prepare the SQL query to search for properties
$searchQuery = "SELECT * FROM properties WHERE 
  property_name LIKE '%$searchTerm%' OR
  location LIKE '%$searchTerm%' OR
  description LIKE '%$searchTerm%' OR
  amenities LIKE '%$searchTerm%'
";

// Execute the search query
$result = $conn->query($searchQuery);

// Create an array to store the search results
$searchResults = array();

if ($result->num_rows > 0) {
    // Loop through the query results and add them to the search results array
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
}

// Close the connection
$conn->close();

// Return the search results in JSON format
header('Content-Type: application/json');
echo json_encode($searchResults);
?>
