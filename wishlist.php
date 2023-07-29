<?php
// wishlist.php
$servername = "localhost";
$username = "eimomio1";
$password = "eimomio1";
$dbname = "eimomio1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the request to add a property to the wishlist
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"]) && $_GET["action"] === "add") {
    $userId = $_GET['userId'];
    $propertyId = $_GET['propertyId'];

    // Prepare and execute the SQL query to insert the property into the wishlist table
    $insertQuery = "INSERT INTO wishlist (user_id, property_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $userId, $propertyId);

    if ($stmt->execute()) {
        // Return success response to the client
        echo json_encode(array("status" => "success"));
        exit;
    } else {
        // Return error response to the client
        echo json_encode(array("status" => "error", "message" => "Error adding property to wishlist."));
        exit;
    }
}

// Handle the request to retrieve the wishlist data for the logged-in user
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"]) && $_GET["action"] === "get") {
    $userId = $_GET['userId'];

    // Prepare and execute the SQL query to retrieve the wishlist data for the user
    $selectQuery = "SELECT properties.* FROM properties INNER JOIN wishlist ON properties.id = wishlist.property_id WHERE wishlist.user_id = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $wishlistData = array();

    // Fetch the wishlist data and store it in an array
    while ($row = $result->fetch_assoc()) {
        $wishlistData[] = $row;
    }

    // Return the wishlist data as a JSON response to the client
    echo json_encode($wishlistData);
    exit;
}
