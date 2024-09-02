<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
//$post_id = $_GET['id'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

if ($post_id <= 0) {
    echo json_encode(['error' => 'Invalid post ID']);
    exit();
}

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user already clicked "Me too" for this post
$sql = "SELECT * FROM user_me_too WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['error' => 'You have already clicked "Me too" for this post']);
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->close();

// Insert into user_me_too
$sql = "INSERT INTO user_me_too (user_id, post_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$stmt->close();

// Update me_too_counts
$sql = "INSERT INTO me_too_counts (post_id, count) VALUES (?, 1) ON DUPLICATE KEY UPDATE count = count + 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();

$stmt->close();
$conn->close();

echo json_encode(['success' => true]);
?>
