<?php
session_start();
//get_wishlist.php
// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['user_type'])) {
  echo json_encode(['error' => 'User not logged in']);
  exit;
}

// Check if the request is a GET request and contains the username parameter
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['username'])) {
  $username = $_GET['username'];

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

  // Retrieve the wishlist items for the current user from the 'wishlistTable'
  $getWishlistQuery = "SELECT * FROM wishlistTable WHERE username = ?";
  $stmt = $conn->prepare($getWishlistQuery);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  $wishlistItems = [];
  while ($row = $result->fetch_assoc()) {
    $wishlistItems[] = [
      'propertyId' => $row['id'],
      'image' => $row['image'],
      'property_name' => $row['property_name'],
      'location' => $row['location'],
      'price' => $row['price'],
      'size' => $row['size'],
      'description' => $row['description'],
      'amenities' => $row['amenities'],
    ];
  }

  echo json_encode($wishlistItems);

  // Close the connection
  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['error' => 'Invalid request method or missing username parameter']);
}
?>
