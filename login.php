<?php
session_start();
//login.php
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
    // Get form inputs
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    // Prepare and execute the SQL query to check the user credentials
    $checkUserQuery = "";
    $specificTable = "";

    if ($userType === 'buyer') {
        $checkUserQuery = "SELECT id, username, password FROM buyers WHERE username = ?";
        $specificTable = "buyers";
    } elseif ($userType === 'seller') {
        $checkUserQuery = "SELECT id, username, password FROM sellers WHERE username = ?";
        $specificTable = "sellers";
    } elseif ($userType === 'admin') {
        $checkUserQuery = "SELECT id, username, password FROM admins WHERE username = ?";
        $specificTable = "admins";
    } else {
        echo 'Invalid user type.';
        exit;
    }

    if (!empty($checkUserQuery) && !empty($specificTable)) {
        $stmt = $conn->prepare($checkUserQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $userData = $result->fetch_assoc();
            $hashedPassword = $userData['password'];

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Password is correct, set up session variables and redirect to the appropriate dashboard
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['username'] = $userData['username'];
                $_SESSION['user_type'] = $userType; // Save user type in session for future reference

                if ($userType === 'buyer') {
                    // Check if this is the buyer's first login
                    $buyerFirstLoginKey = 'buyer_first_login_' . $userData['id'];

                    if (!isset($_SESSION[$buyerFirstLoginKey])) {
                        // Mark first login as acknowledged in the session
                        $_SESSION[$buyerFirstLoginKey] = true;
                        // Store the welcome message in a PHP variable
                        $welcomeMessage = "Thank you for choosing us, dear buyer! Welcome aboard! 🚀🌟";
                    } else {
                        // Store the regular welcome message in a PHP variable
                        $welcomeMessage = "Welcome back, dear buyer! 😊";
                    }

                    // Redirect the user to the buyer dashboard with the welcome message as a query parameter
                    header("Location: buyer_dashboard.html?welcomeMessage=" . urlencode($welcomeMessage));
                    exit;
                } elseif ($userType === 'seller') {
                    header("Location: seller_dashboard.php");
                    exit;
                } elseif ($userType === 'admin') {
                    header("Location: admin_dashboard.php");
                    exit;
                }
            } else {
                echo 'Invalid password. Please try again.';
            }
        } else {
            echo 'User not found. Please check your username and user type.';
        }
    } else {
        echo 'Invalid user type.';
        exit;
    }
}

// Close the connection
$conn->close();
?>
