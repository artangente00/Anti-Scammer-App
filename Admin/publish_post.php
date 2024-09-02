<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // You may want to handle unauthorized access here
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // You may want to handle invalid request method here
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

// Database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the post ID from the POST data
$postId = $_POST['postId'];

// Update the status of the post to 'published' in the database
$query = "UPDATE posts SET status = 'published' WHERE post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $postId);

if ($stmt->execute()) {
    // Return a success response
    echo json_encode(['success' => true, 'message' => 'Post published successfully']);
} else {
    // Return an error response
    echo json_encode(['success' => false, 'message' => 'Failed to publish post']);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
