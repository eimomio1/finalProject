<?php
// Database configuration 
// propertyFile.php
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

// Create the 'properties' table
$createPropertiesTable = "CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_name VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    size INT NOT NULL,
    description TEXT,
    amenities TEXT,
    image VARCHAR(255) NOT NULL
)";

if ($conn->query($createPropertiesTable) === TRUE) {
    echo "Table 'properties' created successfully.<br>";

    // Insert sample data into the 'properties' table
    $insertSampleData = "INSERT INTO properties (property_name, location, price, size, description, amenities, image) VALUES
        ('Luxury Villa', 'New York', 5000000.00, 5000, 'A luxurious villa with beautiful views.', 'Swimming Pool, Garden, Garage', 'property_images/villa.jpg'),
        ('Modern Apartment', 'Los Angeles', 750000.00, 1200, 'A stylish modern apartment in the city center.', 'Balcony, Gym, Parking', 'property_images/apartment.jpg'),
        ('Cozy Cottage', 'Seattle', 300000.00, 800, 'A cozy cottage in a peaceful neighborhood.', 'Fireplace, Garden, Patio', 'property_images/cottage.jpg')";

    if ($conn->query($insertSampleData) === TRUE) {
        echo "Sample data inserted successfully.<br>";
    } else {
        echo "Error inserting sample data: " . $conn->error . "<br>";
    }
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>
