<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$category = $_GET['category'] ?? '';
$subcategory = $_GET['subcategory'] ?? '';

if (empty($category)) {
    echo json_encode([]);
    exit;
}

// Fetch posts based on category and subcategory
$queryStr = 'SELECT * FROM posts WHERE category = ? AND status = "published"';
$params = [$category];
$types = 's';

if (!empty($subcategory)) {
    $queryStr .= ' AND sub_category = ?';
    $params[] = $subcategory;
    $types .= 's';
}

$query = $conn->prepare($queryStr);
if (!$query) {
    die(json_encode(['error' => 'Query preparation failed: ' . $conn->error]));
}

$query->bind_param($types, ...$params);
$query->execute();
$result = $query->get_result();

$posts = [];
$count_ids = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
    $count_ids[] = $row['count_id'];
}

$query->close();

// If no posts found, return empty array
if (empty($posts)) {
    echo json_encode([]);
    $conn->close();
    exit;
}

// Fetch images for the posts
$placeholders = implode(',', array_fill(0, count($count_ids), '?'));
$types = str_repeat('i', count($count_ids));

$sql = "SELECT file_name, file_path, count_id FROM post_images WHERE count_id IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$count_ids);
$stmt->execute();
$result = $stmt->get_result();

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[$row['count_id']] = $row;
}

$stmt->close();
$conn->close();

// Attach images to respective posts
foreach ($posts as &$post) {
    $count_id = $post['count_id'];
    $post['image'] = $images[$count_id] ?? null;
}

echo json_encode($posts);
?>
