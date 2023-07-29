<?php
// get_property_details.php
// Database configuration (same as propertyFile.php)
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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Retrieve property details based on the given property ID
    $propertyId = $_GET['id'];

    $sql = "SELECT * FROM properties WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $propertyId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Property found
        $property = $result->fetch_assoc();
        echo json_encode($property);
    } else {
        // Property not found
        echo json_encode(array());
    }

    $stmt->close();
}

$conn->close();
?>
