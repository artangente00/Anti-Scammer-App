<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /home.php");
    exit();
}

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the request
$search_query = isset($_POST['search_query']) ? $conn->real_escape_string($_POST['search_query']) : '';

// Fetch posts related to the search query from the view with status as 'published'
$sql = "
    SELECT p.*, pi.file_path AS image_url
    FROM view_posts_users AS p
    LEFT JOIN post_images AS pi ON p.count_id = pi.count_id
    INNER JOIN (
        SELECT count_id, MAX(file_path) AS file_path
        FROM post_images
        GROUP BY count_id
    ) AS latest_images ON pi.count_id = latest_images.count_id AND pi.file_path = latest_images.file_path
    WHERE (p.title LIKE '%$search_query%' OR 
           p.scammer_name LIKE '%$search_query%' OR 
           p.count_id LIKE '%$search_query%' OR 
           p.post_id LIKE '%$search_query%' OR 
           p.sc_phone LIKE '%$search_query%' OR 
           p.sc_email LIKE '%$search_query%' OR 
           p.sc_username LIKE '%$search_query%' OR 
           p.sc_bankacctname LIKE '%$search_query%' OR 
           p.sc_bankacctnumber LIKE '%$search_query%' OR 
           p.user_id LIKE '%$search_query%' OR
           p.status LIKE '%$search_query%' OR
           p.user_name LIKE '%$search_query%' OR
           p.category LIKE '%$search_query%') 
           AND p.status = 'published'
    GROUP BY p.post_id";

$result = $conn->query($sql);

if ($result === false) {
    // Log SQL error and output a JSON error message
    $error_message = "SQL error: " . $conn->error;
    error_log($error_message);
    header('Content-Type: application/json');
    echo json_encode(['error' => $error_message]);
    $conn->close();
    exit();
}

$posts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($posts);

$conn->close();
?>
