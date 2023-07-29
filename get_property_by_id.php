
<?php
session_start();
//get_property_by_id.php
// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['user_type'])) {
  echo json_encode(['error' => 'User not logged in']);
  exit;
}

// Check if the request is a GET request and contains the propertyId parameter
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['propertyId'])) {
  $propertyId = $_GET['propertyId'];

  // Database configuration (you can put this in a separate file for better organization)
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

  // Retrieve the property details from the 'properties' table based on the property ID
  $getPropertyQuery = "SELECT * FROM properties WHERE id = ?";
  $stmt = $conn->prepare($getPropertyQuery);
  $stmt->bind_param("i", $propertyId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    // Property found, retrieve the details
    $property = $result->fetch_assoc();
    echo json_encode($property);
  } else {
    echo json_encode(['error' => 'Property not found']);
  }

  // Close the connection
  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['error' => 'Invalid request method or missing propertyId parameter']);
}
?>
