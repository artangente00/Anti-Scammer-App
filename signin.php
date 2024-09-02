<?php
session_start();
header('Content-Type: application/json');

// Database configuration
//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to fetch user details based on email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // User found, check password and status
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Check user status
            if ($user['status'] == 'verified') {
                // Set user session data
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_role'] = $user['user_role'];
                $_SESSION['firstmid_name'] = $user['firstmid_name'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['birthday'] = $user['birthday'];
                $_SESSION['phone'] = $user['phone']; // Store first and middle name in session
                $_SESSION['country'] = $user['country'];
                $_SESSION['id_image'] = $user['id_image'];
                $_SESSION['date_registered'] = $user['date_registered'];
                $_SESSION['status'] = $user['status']; // Store first and middle name in session

                // Log the session start
                $user_id = $_SESSION['user_id'];
                $sql_signin = "INSERT INTO user_sessions (user_id, session_start) VALUES (?, NOW())";
                $stmt_signin = $conn->prepare($sql_signin);
                $stmt_signin->bind_param("i", $user_id);
                $stmt_signin->execute();

                // Log the activity
                $sql_activity = "INSERT INTO user_activities (user_id, activity_type, description, timestamp)
                                VALUES (?, 'user signed in', 'User signed in to the system.', NOW())";
                $stmt_activity = $conn->prepare($sql_activity);
                $stmt_activity->bind_param("i", $user_id);
                $stmt_activity->execute();

                $stmt_signin->close();
                $stmt_activity->close();

                // Redirect to the appropriate page based on user role
                if ($user['user_role'] == 'admin') {
                    echo json_encode(["status" => "success", "message" => "Welcome, Admin {$user['firstmid_name']}!", "redirect" => "/App/admin/homeadmin.php"]);
                } elseif ($user['user_role'] == 'user') {
                    echo json_encode(["status" => "success", "message" => "Welcome, User {$user['firstmid_name']}!", "redirect" => "/App/users/homeusers.php"]);
                }
            } else {
                // User is not verified or is blocked
                echo json_encode(["status" => "error", "message" => "User is not verified or is blocked. Please contact us for verification."]);
            }
        } else {
            // Invalid password
            echo json_encode(["status" => "error", "message" => "Invalid password."]);
        }
    } else {
        // User not found
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
}

// Close the connection
$conn->close();
?>
