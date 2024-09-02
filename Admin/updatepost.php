<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /home.php");
    exit();
}

// Database configuration
//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

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

$post_id = $_POST['post_id'];
$title = $_POST['title'];
$category = $_POST['category'];
$sub_category = $_POST['sub_category'];
$scammer_name = $_POST['scammer_name'];
$description = $_POST['description'];
$fb_link = $_POST['fb_link'];
$sc_phone = $_POST['sc_phone'];
$sc_email = $_POST['sc_email'];
$sc_bankname = $_POST['sc_bankname'];
$sc_bankacctnumber = $_POST['sc_bankacctnumber'];
$sc_username = $_POST['sc_username'];


// Update the post in the database
$query = "UPDATE posts SET title = ?, category = ?, sub_category = ?, scammer_name = ?, description = ?, fb_link = ?, sc_phone = ?, sc_email = ?, sc_bankname = ?, sc_bankacctnumber = ?, sc_username = ? WHERE post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('sssssssssssi', $title, $category, $sub_category, $scammer_name, $description, $fb_link, $sc_phone, $sc_email, $sc_bankname, $sc_bankacctnumber, $sc_username,  $post_id);

if ($stmt->execute()) {
    echo "<script>
            alert('Post updated successfully.');
            window.location.href = 'users.php';
          </script>";
} else {
    echo "<script>
            alert('Error updating post: " . addslashes($conn->error) . "');
          </script>";
}


$conn->close();
?>
