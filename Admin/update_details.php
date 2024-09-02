<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field = $_POST['field'];
    $value = trim($_POST[$field]);

    // Debugging: Log received data
    error_log("Field: " . $field);
    error_log("Value: " . $value);

    // Validate input
    if (empty($value)) {
        $response['message'] = 'Field value cannot be empty';
        echo json_encode($response);
        exit;
    }

    // Define valid fields to update
    $validFields = ['user_name', 'firstmid_name', 'last_name', 'email', 'phone'];
    if (!in_array($field, $validFields)) {
        $response['message'] = 'Invalid field';
        echo json_encode($response);
        exit;
    }

    // Sanitize input
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

    // Database configuration
    //$hostname = '195.26.253.211'; // Change this to your database hostname
    //$username = 'admin'; // Change this to your database username
    //$password = 'admin'; // Change this to your database password
    //$database = 'test'; // Change this to your database name

    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'test';

    // Create connection
    $conn = new mysqli($hostname, $username, $password, $database);

    if ($conn->connect_error) {
        $response['message'] = 'Database connection failed: ' . $conn->connect_error;
        echo json_encode($response);
        exit;
    }

    // Get current user status from session
    $status = $_SESSION['status'];

    $userId = $_SESSION['user_id']; // Assuming user_id is stored in the session

    // Update user's details and set status to 'unverified'
    $stmt = $conn->prepare("UPDATE users SET $field = ?, status = 'unverified' WHERE user_id = ?");
    $stmt->bind_param('si', $value, $userId);

    if ($stmt->execute()) {
        // Update session variable with new value
        $_SESSION[$field] = $value;
        // Update status in session
        $_SESSION['status'] = 'unverified';
        $response['success'] = true;
    } else {
        $response['message'] = 'Database update failed';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
