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

// SQL query to fetch data
$sql = "SELECT posts.post_id, posts.title, posts.date_posted, users.email 
        FROM posts 
        JOIN users ON posts.user_id = users.user_id";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} 

$conn->close();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
