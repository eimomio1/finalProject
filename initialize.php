
<?php
// Database configuration 
// initialize.php
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

// Drop the 'properties' table if it exists
$dropTableQuery = "DROP TABLE IF EXISTS properties";

if ($conn->query($dropTableQuery) === TRUE) {
    echo "Table 'properties' dropped successfully.<br>";
} else {
    echo "Error dropping table: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>
