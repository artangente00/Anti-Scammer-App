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

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Get the user_name from the session
$user_name = $_SESSION['user_name'];

// Get the post_id and dispute_text from the form submission
// Get values from AJAX request
$post_id = $_POST['post_id'];
$comment_text = $_POST['comment_text'];


// Prepare the SQL statement
$sql = "INSERT INTO comments (post_id, user_id, username_comment, comment_text) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $post_id, $user_id, $user_name, $comment_text);

// Execute the statement
if ($stmt->execute()) {
    echo "<script>alert('Comment posted successfully!'); window.location.href = 'posts.php?id=$post_id';</script>";
} else {
    echo "<script>alert('Error: Could not submit your comment.'); window.location.href = 'posts.php?id=$post_id';</script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
