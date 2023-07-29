
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['user_type'])) {
  echo json_encode(['error' => 'User not logged in']);
  exit;
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the property ID from the request body
  $requestData = json_decode(file_get_contents('php://input'), true);
  $propertyId = $requestData['propertyId'];

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

    // Insert the property details into the 'wishlistTable' along with the current user's username
    $insertPropertyQuery = "INSERT INTO wishlistTable (username, property_name, location, price, size, description, amenities, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertPropertyQuery);
    $stmt->bind_param("sssdisss", $_SESSION['username'], $property['property_name'], $property['location'], $property['price'], $property['size'], $property['description'], $property['amenities'], $property['image']);

    if ($stmt->execute()) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => 'Failed to add property to wishlist']);
    }

    $stmt->close();
  } else {
    echo json_encode(['error' => 'Property not found']);
  }

  // Close the connection
  $conn->close();
} else {
  echo json_encode(['error' => 'Invalid request method']);
}
?>
