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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];
    $username_reply = $_SESSION['user_name'];
    $reply_text = $_POST['reply_text'];

    if (empty($reply_text)) {
        echo "Reply text cannot be empty.";
        exit();
    }

    $sql = "INSERT INTO reply (post_id, comment_id, user_id, username_reply, reply_text) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiss", $post_id, $comment_id, $user_id, $username_reply, $reply_text);
    if ($stmt->execute()) {
        echo "<script>alert('Reply posted successfully!'); window.location.href = 'posts.php?id=$post_id';</script>";
   
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
