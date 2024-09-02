<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Check if file was uploaded without errors
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $targetDir = "/Applications/XAMPP/htdocs/App/uploads/"; // Directory where uploaded files will be stored
        $targetFile = $targetDir . basename($_FILES["image"]["name"]); // Path of the uploaded file on the server

        // Check if file already exists
        if (file_exists($targetFile)) {
            echo "Sorry, the file already exists.";
        } else {
            // Move uploaded file to specified directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "The file ". htmlspecialchars(basename($_FILES["image"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Error: Please select a file to upload.";
    }
}
?>
