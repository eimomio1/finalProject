<?php
// login.php

session_start(); // Start the session

// Database configuration
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    // Validate username and password presence
    if (empty($username) || empty($password) || empty($userType)) {
        echo 'Please enter username, password, and select user type.';
        exit;
    }

    $specificTable = "";
    $fetchPasswordQuery = "";
    if ($userType === 'buyer') {
        $specificTable = "buyers";
    } elseif ($userType === 'seller') {
        $specificTable = "sellers";
    } elseif ($userType === 'admin') {
        $specificTable = "admins";
    } else {
        echo 'Invalid user type.';
        exit;
    }

    // Prepare and execute the query to fetch the user's hashed password from the database
    $fetchPasswordQuery = "SELECT id, password FROM " . $specificTable . " WHERE username = ?";
    $stmt = $conn->prepare($fetchPasswordQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify the user's entered password with the hashed password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, set up session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $userType;

            // Redirect users based on user type
            if ($userType === 'buyer') {
                header("Location: buyer_dashboard.html");
                exit;
            } elseif ($userType === 'seller') {
                header("Location: seller_dashboard.html");
                exit;
            } elseif ($userType === 'admin') {
                header("Location: admin_dashboard.html");
                exit;
            }
        } else {
            echo 'Invalid password. Please try again.';
            exit;
        }
    } else {
        echo 'User not found. Please check your username and user type.';
        exit;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
