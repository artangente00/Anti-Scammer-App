<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // If user is not logged in, return an error response
    echo json_encode(['error' => 'You are not logged in.']);
    exit();
}

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // If connection fails, return an error response
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Get the post_id and dispute_text from the AJAX request
$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : null;
$dispute_text = isset($_POST['dispute_text']) ? $_POST['dispute_text'] : '';

// Check if the post has already been disputed by any user
$sql_check_dispute = "SELECT * FROM post_disputes WHERE post_id = ?";
$stmt_check_dispute = $conn->prepare($sql_check_dispute);
$stmt_check_dispute->bind_param("i", $post_id);
$stmt_check_dispute->execute();
$result_check_dispute = $stmt_check_dispute->get_result();

if ($result_check_dispute->num_rows > 0) {
    // If post has already been disputed, return an error response
    echo json_encode(['error' => 'This post has already been disputed by another user.']);
    exit();
}

// Prepare the SQL statement for inserting dispute
$sql_insert_dispute = "INSERT INTO post_disputes (post_id, user_id, dispute_text) VALUES (?, ?, ?)";
$stmt_insert_dispute = $conn->prepare($sql_insert_dispute);
$stmt_insert_dispute->bind_param("iis", $post_id, $user_id, $dispute_text);

// Execute the statement for inserting dispute
if ($stmt_insert_dispute->execute()) {
    // If dispute is inserted successfully, return a success response
    echo json_encode(['success' => true]);
} else {
    // If error occurs while inserting dispute, return an error response
    echo json_encode(['error' => 'Failed to submit your dispute.']);
}

// Close the statements and connection
$stmt_check_dispute->close();
$stmt_insert_dispute->close();
$conn->close();
?>
