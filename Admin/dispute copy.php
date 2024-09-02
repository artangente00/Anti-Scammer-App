<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /home.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Get the user_name from the session
$user_name = $_SESSION['user_name'];

// Get the post_id and dispute_text from the form submission
$post_id = $_POST['post_id'];
$dispute_text = $_POST['dispute_text'];

// Check if the post has already been disputed by any user
$sql_check_dispute = "SELECT * FROM post_disputes WHERE post_id = ?";
$stmt_check_dispute = $conn->prepare($sql_check_dispute);
$stmt_check_dispute->bind_param("i", $post_id);
$stmt_check_dispute->execute();
$result_check_dispute = $stmt_check_dispute->get_result();

if ($result_check_dispute->num_rows > 0) {
    // Post has already been disputed by another user, handle the error
    echo "<script>alert('This post has already been disputed by another user.'); window.location.href = 'posts.php?id=$post_id';</script>";
    exit();
}

// Prepare the SQL statement for inserting dispute
$sql_insert_dispute = "INSERT INTO post_disputes (post_id, user_id, username_dispute, dispute_text) VALUES (?, ?, ?, ?)";
$stmt_insert_dispute = $conn->prepare($sql_insert_dispute);
$stmt_insert_dispute->bind_param("iiss", $post_id, $user_id, $user_name, $dispute_text);

// Execute the statement for inserting dispute
if ($stmt_insert_dispute->execute()) {
    echo "<script>alert('Your dispute has been submitted successfully.'); window.location.href = 'posts.php?id=$post_id';</script>";
} else {
    echo "<script>alert('Error: Could not submit your dispute.'); window.location.href = 'posts.php?id=$post_id';</script>";
}

// Close the statements and connection
$stmt_check_dispute->close();
$stmt_insert_dispute->close();
$conn->close();
?>
