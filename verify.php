<?php
// Enable error reporting for debugging purposes
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//header('Content-Type: application/json');
require 'vendor/autoload.php';

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    $errorMessage = "Connection failed: " . $conn->connect_error;
    error_log($errorMessage); // Log connection error
    echo json_encode(["status" => "error", "message" => $errorMessage]);
    exit();
}

// Function to fetch token from URL
function getTokenFromURL() {
    // Check if the token parameter is set in the URL
    if (isset($_GET['token'])) {
        // Return the token value
        return $_GET['token'];
    } else {
        // Return null if token parameter is not set
        return null;
    }
}

$token = getTokenFromURL();
if ($token !== null) {
    // Check if the token is valid
    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_token = ?");
    if (!$stmt) {
        $errorMessage = "Prepare failed: " . $conn->error;
        error_log($errorMessage); // Log error
        echo json_encode(["status" => "error", "message" => $errorMessage]);
        exit();
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, fetch user data
        $user = $result->fetch_assoc();

        // Update the user's status to verified
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1, status = 'verified' WHERE verification_token = ?");
        if (!$stmt) {
            $errorMessage = "Prepare failed: " . $conn->error;
            error_log($errorMessage); // Log error
            echo json_encode(["status" => "error", "message" => $errorMessage]);
            exit();
        }

        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            // Output HTML with JavaScript to show an alert
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Email Verification</title>
                <script type="text/javascript">
                    alert("Email verified successfully. You can log in to Sagishi App.");
                    window.location.href = "https://sagishi.com/"; // Redirect to home or any other page
                </script>
            </head>
            <body>
            </body>
            </html>';
        } else {
            $errorMessage = "Verification failed: " . $stmt->error;
            error_log($errorMessage); // Log error
            echo json_encode(["status" => "error", "message" => $errorMessage]);
        }

        $stmt->close();
    } else {
        $errorMessage = "Invalid token.";
        error_log($errorMessage); // Log error
        echo json_encode(["status" => "error", "message" => $errorMessage]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Token not found in URL."]);
}

$conn->close();
?>
