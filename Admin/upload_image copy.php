<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload'])) {
    $file = $_FILES['upload'];
    $uploadDir = 'images/';
    $uploadFile = $uploadDir . basename($file['name']);

    // Ensure upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Log file information for debugging
    error_log("Uploading file: " . $file['name']);
    error_log("Temporary file location: " . $file['tmp_name']);
    error_log("Destination path: " . $uploadFile);

    // Check if the file was uploaded without errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Attempt to move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            $response = [
                "url" => "http://localhost/App/admin/" . $uploadFile
            ];
        } else {
            // Log error if file move fails
            error_log("Could not move the uploaded file to $uploadFile");
            $response = [
                "error" => [
                    "message" => "Could not move the uploaded file to $uploadFile"
                ]
            ];
        }
    } else {
        // Log different upload errors
        $response = [
            "error" => [
                "message" => "File upload error: " . $file['error']
            ]
        ];
    }

    echo json_encode($response);
} else {
    $response = [
        "error" => [
            "message" => "No file uploaded or wrong request method."
        ]
    ];
    echo json_encode($response);
}
?>
