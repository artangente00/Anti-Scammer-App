<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if the user is logged in by verifying the session variables
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Query to fetch user details based on user_id
    $query = "SELECT user_name, firstmid_name, last_name, email, phone, country, id_image FROM users WHERE id = '$user_id'";
    $result = $conn->query($query);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        echo json_encode(["status" => "success", "data" => $user]);
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
}

// Close the connection
$conn->close();
?>
