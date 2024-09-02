<?php
header('Content-Type: application/json');
require 'vendor/autoload.php';
use SendGrid\Mail\Mail;

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log connection error
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Function to handle file upload
function uploadImage($fileInputName, $targetDirectory) {
    $targetFile = $targetDirectory . basename($_FILES[$fileInputName]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    $check = getimagesize($_FILES[$fileInputName]["tmp_name"]);
    if ($check === false) {
        error_log("File is not an image."); // Log error
        return ["status" => "error", "message" => "File is not an image."];
        $uploadOk = 0;
    }

    // Check file size (max 500KB)
    if ($_FILES[$fileInputName]["size"] > 500000) {
        error_log("File is too large."); // Log error
        return ["status" => "error", "message" => "Sorry, your file is too large."];
        $uploadOk = 0;
    }

    // Allow only certain file formats (JPEG, PNG, GIF)
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        error_log("Invalid file format."); // Log error
        return ["status" => "error", "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."];
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        error_log("File was not uploaded."); // Log error
        return ["status" => "error", "message" => "Sorry, your file was not uploaded."];
    } else {
        // Attempt to upload file
        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
            return ["status" => "success", "filename" => basename($_FILES[$fileInputName]["name"])];
        } else {
            error_log("Error uploading file."); // Log error
            return ["status" => "error", "message" => "Sorry, there was an error uploading your file."];
        }
    }
}

// Function to send signup notification using SendGrid
function sendSignupNotification($adminEmails, $username, $userEmail, $firstmid_name, $last_name) {
    $sendgrid = new \SendGrid('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');

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("marketing@pageantcentral.co", "Sagishi"); // Replace with your verified email
    $email->setSubject("New User Signup Notification");

    foreach ($adminEmails as $adminEmail) {
        $email->addTo($adminEmail);
    }

    $content = "New user signed up.\n\nUsername: $username\nEmail: $userEmail\nName: $firstmid_name $last_name\n\nPlease verify the user, https://sagishi.com/";
    $email->addContent("text/plain", $content);

    try {
        $response = $sendgrid->send($email);
        if ($response->statusCode() == 202) {
            return true;
        } else {
            error_log('SendGrid Error: ' . $response->body()); // Log SendGrid error
            error_log('SendGrid Status Code: ' . $response->statusCode());
            error_log('SendGrid Headers: ' . json_encode($response->headers()));
            return false;
        }
    } catch (Exception $e) {
        error_log('Caught exception: ' . $e->getMessage()); // Log exception
        return false;
    }
}

// Function to send verification email to user
function sendVerificationEmail($userEmail, $verificationToken) {
    $sendgrid = new \SendGrid('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');
    
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("no-reply@pageantcentral.co", "Sagishi"); // Replace with your verified email
    $email->setSubject("Email Verification");

    $email->addTo($userEmail);

    $verificationLink = "http://localhost/App/verify.php?token=$verificationToken"; // Replace with your domain and verification script path
    $content = "Thank you for signing up.\n\nPlease click the following link to verify your email address:\n$verificationLink";
    $email->addContent("text/plain", $content);

    try {
        $response = $sendgrid->send($email);
        if ($response->statusCode() == 202) {
            return true;
        } else {
            error_log('SendGrid Error: ' . $response->body()); // Log SendGrid error
            error_log('SendGrid Status Code: ' . $response->statusCode());
            error_log('SendGrid Headers: ' . json_encode($response->headers()));
            return false;
        }
    } catch (Exception $e) {
        error_log('Caught exception: ' . $e->getMessage()); // Log exception
        return false;
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $firstmid_name = $_POST['firstmid_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday']; // Capture the birthday
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $agreeTerms = isset($_POST['agreeTerms']) ? 1 : 0;

    // Validate phone number
    if (!preg_match('/^\d{10,15}$/', $phone)) {
        error_log("Invalid phone number: $phone"); // Log phone number validation error
        echo json_encode(["status" => "error", "message" => "Invalid phone number. It must contain only digits and be between 10 and 15 characters long."]);
        exit();
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ? OR email = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error); // Log error
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("ss", $user_name, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        error_log("Username or email already exists: $user_name, $email"); // Log error
        echo json_encode(["status" => "error", "message" => "Username or email already exists."]);
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generate verification token
    $verification_token = bin2hex(random_bytes(16));

    // File upload handling
    $uploadResult = uploadImage("id_image", "/Applications/XAMPP/htdocs/App/uploads/");

    if ($uploadResult["status"] == "success") { // Proceed if image upload is successful
        $idImage = $uploadResult["filename"];
        // Insert data into database
        $sql = "INSERT INTO users (user_name, firstmid_name, last_name, birthday, password, email, phone, country, id_image, verification_token)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error); // Log error
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
            exit();
        }

        $stmt->bind_param("ssssssssss", $user_name, $firstmid_name, $last_name, $birthday, $hashed_password, $email, $phone, $country, $idImage, $verification_token);

        if ($stmt->execute()) {
            // Send verification email to user
            $verificationSent = sendVerificationEmail($email, $verification_token);

            // Send notification to admins
            $adminEmails = ["artangente00@gmail.com", "Verify@sagishi.com"]; // Add all admin emails here
            $notificationSent = sendSignupNotification($adminEmails, $user_name, $email, $firstmid_name, $last_name);

            if ($verificationSent) {
                echo json_encode(["status" => "success", "message" => "Registration successful. Please check your email to verify your account."]);
            } else {
                error_log("Failed to send verification email to user."); // Log error
                echo json_encode(["status" => "error", "message" => "Registration successful, but failed to send verification email."]);
            }
        } else {
            error_log("Registration failed: " . $stmt->error); // Log error
            echo json_encode(["status" => "error", "message" => "Error: Registration failed."]);
        }

        $stmt->close();
    } else {
        error_log("File upload failed: " . json_encode($uploadResult)); // Log file upload error
        echo json_encode($uploadResult); // Return the error message from the file upload
    }
}

$conn->close();
?>
