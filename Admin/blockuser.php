<?php
// Retrieve user ID from the URL parameter
$user_id = $_GET['id']; // Assuming the URL parameter is named 'id'

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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to update user status
$sql = "UPDATE users SET status='blocked' WHERE user_id=?";
$stmt = $conn->prepare($sql);

// Bind parameters and execute the statement
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Check if the query was successful
if ($stmt->affected_rows > 0) {
    // User status updated successfully
    http_response_code(200); // Set HTTP status code to 200 (OK)
} else {
    // Failed to update user status
    http_response_code(500); // Set HTTP status code to 500 (Internal Server Error)
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
