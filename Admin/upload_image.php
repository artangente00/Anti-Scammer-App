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
    $uploadDir = '/Applications/XAMPP/htdocs/App/admin/uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    // Ensure upload directory exists
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            error_log("Failed to create directory: $uploadDir");
            echo json_encode(["error" => ["message" => "Failed to create upload directory"]]);
            exit;
        }
    }

    // Log file information for debugging
    error_log("Uploading file: " . $file['name']);
    error_log("Temporary file location: " . $file['tmp_name']);
    error_log("Destination path: " . $uploadFile);

    // Check if the file was uploaded without errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Check for a potential duplicate file
        if (file_exists($uploadFile)) {
            error_log("File already exists: $uploadFile");
            echo json_encode(["error" => ["message" => "File already exists"]]);
            exit;
        }

        // Attempt to move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            $response = [
                "url" => "http://localhost/App/admin/uploads/" . basename($uploadFile)
            ];
        } else {
            // Log error if file move fails
            error_log("Could not move the uploaded file to $uploadFile.");
            echo json_encode(["error" => ["message" => "Could not move the uploaded file to $uploadFile"]]);
            exit;
        }
    } else {
        // Handle different file upload errors
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE   => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
            UPLOAD_ERR_FORM_SIZE  => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
            UPLOAD_ERR_PARTIAL    => "The uploaded file was only partially uploaded.",
            UPLOAD_ERR_NO_FILE    => "No file was uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION  => "A PHP extension stopped the file upload."
        ];

        $errorMessage = isset($uploadErrors[$file['error']]) ? $uploadErrors[$file['error']] : "Unknown upload error.";
        error_log("File upload error: " . $errorMessage);
        echo json_encode(["error" => ["message" => $errorMessage]]);
        exit;
    }

    echo json_encode($response);
} else {
    echo json_encode(["error" => ["message" => "No file uploaded or wrong request method."]]);
}
?>
