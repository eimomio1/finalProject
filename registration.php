<?php
// Database configuration 
//registration.php
$servername = "localhost";
$username = "eimomio1";
$password = "eimomio1";
$dbname = "eimomio1";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create "buyers" table if it does not exist
$createBuyersTable = "CREATE TABLE IF NOT EXISTS buyers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

$conn->query($createBuyersTable);

// Create "sellers" table if it does not exist
$createSellersTable = "CREATE TABLE IF NOT EXISTS sellers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

$conn->query($createSellersTable);

// Create "admins" table if it does not exist
$createAdminsTable = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

$conn->query($createAdminsTable);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form inputs
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];
    
    // Basic validation for each field
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($userType)) {
        echo 'Please fill in all fields.';
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if the username already exists in the specific table
    $checkUsernameQuery = "";
    $specificTable = "";

    if ($userType === 'buyer') {
        $checkUsernameQuery = "SELECT id FROM buyers WHERE username = ?";
        $specificTable = "buyers";
    } elseif ($userType === 'seller') {
        $checkUsernameQuery = "SELECT id FROM sellers WHERE username = ?";
        $specificTable = "sellers";
    } elseif ($userType === 'admin') {
        $checkUsernameQuery = "SELECT id FROM admins WHERE username = ?";
        $specificTable = "admins";
    } else {
        echo 'Invalid user type.';
        exit;
    }

    if (!empty($checkUsernameQuery) && !empty($specificTable)) {
        $stmt = $conn->prepare($checkUsernameQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
             echo 'Username already exists. Please choose a different username.';
    echo '<br>';
    echo '<button onclick="goBack()">Go Back</button>';
    echo '<script>';
    echo 'function goBack() {';
    echo '  window.history.back();';
    echo '}';
    echo '</script>';
    exit;
            exit;
        }
    } else {
        echo 'Invalid user type.';
        exit;
    }

    // Prepare and execute the SQL query to insert the user data into the appropriate table based on user type
    $insertQuery = "INSERT INTO " . $specificTable . " (firstName, lastName, email, username, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $username, $hashedPassword);

    if ($stmt->execute()) {
     
    header("Location: login.html");
    exit;
} else {
    echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>