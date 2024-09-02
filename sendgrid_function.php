<?php
require '/App/vendor/autoload.php';

// Function to send signup notification using SendGrid
function sendSignupNotification($adminEmail, $username, $userEmail) {
    $sendgrid = new \SendGrid('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');

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("marketing@pageantcentral.co", "Your Website"); // Replace with your verified email
    $email->setSubject("New User Signup Notification");
    $email->addTo($adminEmail);

    $content = "New user signed up.\n\nUsername: $username\nEmail: $userEmail";
    $email->addContent("text/plain", $content);

    try {
        $response = $sendgrid->send($email);
        if ($response->statusCode() == 202) {
            return true;
        } else {
            error_log('SendGrid Error: ' . $response->body());
            error_log('SendGrid Status Code: ' . $response->statusCode());
            error_log('SendGrid Headers: ' . json_encode($response->headers()));
            return false;
        }
    } catch (Exception $e) {
        error_log('Caught exception: ' . $e->getMessage());
        return false;
    }
}

// Example user signup script
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $userEmail = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Database configuration
    $hostname = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $database = 'test';

    // Create connection
    $conn = new mysqli($hostname, $dbUsername, $dbPassword, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM testuser WHERE user_name = ? OR email = ?");
    $stmt->bind_param("ss", $username, $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already exists.";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO testuser (user_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $userEmail, $password);

    if ($stmt->execute()) {
        // Send notification to admin
  //      $adminEmail = "artangente00@gmail.com";
//        $notificationSent = sendSignupNotification($adminEmail, $username, $userEmail);

        if ($notificationSent) {
            echo "Signup successful. Notification sent to admin.";
        } else {
            echo "Signup successful. Failed to send notification to admin.";
        }
    } else {
        echo "Signup failed.";
    }

    $stmt->close();
    $conn->close();
}
?>

