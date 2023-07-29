
<?php
session_start();
//get_current_user.php
// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['user_type'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Prepare the user information to be sent back as JSON
$userInfo = [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['username'],
    'userType' => $_SESSION['user_type'],
];

// Send the user information as JSON response
header('Content-Type: application/json');
echo json_encode($userInfo);
?>
