<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /home.php");
    exit();
}

// Check if the user's status is verified
if ($_SESSION['status'] !== 'verified') {
    // Display alert message
    echo "<script>alert('Your account is unverified. Please contact our support.'); window.location.href = '/App/users/homeusers.php';</script>";
    exit(); // Exit script to prevent further execution
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to upload multiple images
function uploadMultipleImages($conn, $user_id, $count_id) {
    $uploadDirectory = "/Applications/XAMPP/htdocs/App/uploads/";
    
    // Check if the upload directory exists, if not, create it
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }
    
    $errors = [];
    $uploadedFiles = [];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $fileName = basename($_FILES['images']['name'][$key]);
        $fileTmpName = $_FILES['images']['tmp_name'][$key];
        $fileSize = $_FILES['images']['size'][$key];
        $fileError = $_FILES['images']['error'][$key];
        $fileType = $_FILES['images']['type'][$key];

        // Check for errors
        if ($fileError === UPLOAD_ERR_OK) {
            $fileDestination = $uploadDirectory . $fileName;
            $filePath = "/App/uploads/" . $fileName;

            // Move the file to the destination directory
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $uploadedFiles[] = $fileName;

                // Insert file information into the post_images table
                $stmt = $conn->prepare("INSERT INTO post_images (file_name, file_path, user_id, count_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $fileName, $filePath, $user_id, $count_id);
                
                if (!$stmt->execute()) {
                    $errors[] = "Database error: " . $stmt->error;
                }
                
                $stmt->close();
            } else {
                $errors[] = "Error uploading file $fileName.";
            }
        } else {
            $errors[] = "Error uploading file $fileName. Error code: $fileError";
        }
    }

    $message = "";
    
    if (!empty($uploadedFiles)) {
        $message .= "The following files were uploaded successfully:\\n";
        foreach ($uploadedFiles as $uploadedFile) {
            $message .= "- $uploadedFile\\n";
        }
    }
    
    if (!empty($errors)) {
        $message .= "The following errors occurred:\\n";
        foreach ($errors as $error) {
            $message .= "- $error\\n";
        }
    }
    
    if ($message !== "") {
        echo "<script>alert('$message');</script>";
    }
    
    return [
        'success' => empty($errors),
        'errors' => $errors,
        'uploadedFiles' => $uploadedFiles
    ];
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

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

// Fetch the existing content for ids 1, 2, 3, and 4
$result = $conn->query("SELECT id, content, status, description FROM editpage WHERE id IN (1, 2, 3, 4)");
if ($result === false) {
    die("Query failed: " . $conn->error);
}

// Initialize an array to hold the fetched data
$data = [];

// Fetch each row and store it in the array
while ($row = $result->fetch_assoc()) {
    $data[$row['id']] = [
        'content' => $row['content'],
        'status' => $row['status'],
        'description' => $row['description']
    ];
}

// Now $data contains the fetched rows for ids 1, 2, 3, and 4
// Access data for id = 1
$content_1 = $data[1]['content'];
$status_1 = $data[1]['status'];
$description_1 = $data[1]['description'];

// Access data for id = 2
$content_2 = $data[2]['content'];
$status_2 = $data[2]['status'];
$description_2 = $data[2]['description'];

// Access data for id = 3
$content_3 = $data[3]['content'];
$status_3 = $data[3]['status'];
$description_3 = $data[3]['description'];

// Access data for id = 4
$content_4 = $data[4]['content'];
$status_4 = $data[4]['status'];
$description_4 = $data[4]['description'];

// Initialize variable to store alert message
$alertMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $count_id = $_POST['count_id'];
    $category = $_POST['category'];
    $subcategory = isset($_POST['sub_category']) ? $_POST['sub_category'] : null;
    $title = $_POST['title'];
    $scammer_name = $_POST['scammer_name'];
    $facebook_link = $_POST['fb_link'];
    $phone_number = $_POST['sc_phone'];
    $email = $_POST['sc_email'];
    $username = $_POST['sc_username'];
    $bank_name = $_POST['sc_bankname'];
    $account_name = $_POST['sc_bankacctname'];
    $account_number = $_POST['sc_bankacctnumber'];
    $description = $_POST['description'];
    $agreed_terms = isset($_POST['agreed_terms']) ? 'agreed' : 'not agreed';

    // Handle QR code image upload
    $targetDir = "/Applications/XAMPP/htdocs/App/uploads/";
    $qrCode = $_FILES['qr_img']['name'];
    $qrCodePath = $targetDir . basename($qrCode);
    if (!move_uploaded_file($_FILES['qr_img']['tmp_name'], $qrCodePath)) {
        echo "<script>alert('Error uploading QR code image.');</script>";
        exit();
    }

    // Call function to upload multiple images
    $uploadResult = uploadMultipleImages($conn, $user_id, $count_id);

    // Check if there were errors during file upload
    if (!$uploadResult['success']) {
        // Display errors and stop script execution
        foreach ($uploadResult['errors'] as $error) {
            echo "<script>alert('Error: $error');</script>";
        }
        exit();
    }

    // Insert data into the posts table using prepared statements
    $stmt = $conn->prepare("INSERT INTO posts (user_id, count_id, category, sub_category, title, scammer_name, fb_link, sc_phone, sc_email, sc_username, sc_bankname, sc_bankacctname, sc_bankacctnumber, description, qr_img, agreed_terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssssssss", $user_id, $count_id, $category, $subcategory, $title, $scammer_name, $facebook_link, $phone_number, $email, $username, $bank_name, $account_name, $account_number, $description, $qrCodePath, $agreed_terms);

    if ($stmt->execute()) {
        // Set alert message
        echo "<script>alert('Post successfully posted!'); window.location.href = 'usersadmin.php';</script>";
    } else {
        echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
    }

    $stmt->close();
}

// Close the connection to free up resources
$conn->close();
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Scam</title>
    <link rel="stylesheet" href="css/home2.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Link to Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Link to intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <!-- Link to Captcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
    /* Form Style */
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 10px;
        }

        /* Label Style */
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Input Style */
        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* File Input Style */
        input[type="file"] {
            margin-bottom: 10px;
        }

        /* Submit Button Style */
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Textarea Style */
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Styling for the form title */
        form p {
            margin: 0;
            padding: 0;
        }

        /* Styling for the form section divider */
        hr {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Styling for form section title */
        .form-section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .post-scam-heading {
            color: #fff;
            font-size: 28px;
            font-weight: 300;
            text-align: center; /* Align text at the center */
            width: 100%;
            margin: 0 auto; /* Center horizontally */
        }

    </style>
    <script>
        // Object containing categories and their corresponding subcategories
        const subcategories = {
            "HOME & GARDEN": ["Furniture", "Kitchenware", "Gardening tools & Supplies", "Cleaning Materials", "Food"],
            "ENTERTAINMENT": ["Music, Books, Movies", "Event Tickets", "Toys & Games", "Pageant", "Sing/Dance Competition", "GFE", "Massage"],
            "FAMILY": ["Health & Beauty", "Baby & Kids", "Clothes", "Accessories", "Jewelry", "Pet Accessories", "Pet Food", "Pet Purchase", "Wedding Supplier", "Wedding Coordinator"],
            "ELECTRONICS": ["Video Games", "Personal Computer", "Laptop", "Tablet", "Phone", "Accessories"],
            "HOBBIES": ["Restaurant", "Amusement Park", "Musical Instrument", "Sports", "Collections", "Cars for sale", "Parts"],
            "BUSINESS": ["Investment", "Loan", "Supplier", "Partnership"]
        };

            // Event listener to update subcategories when category changes
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("category").addEventListener("change", populateSubcategories);
    });

    // Function to populate subcategories based on selected category
    function populateSubcategories() {
        const categorySelect = document.getElementById("category");
        const subcategorySelect = document.getElementById("subcategory");

        // Clear existing subcategories
        subcategorySelect.innerHTML = "";

        // Get the selected category
        const selectedCategory = categorySelect.value;

        // Get the subcategories for the selected category
        const options = subcategories[selectedCategory] || [];

        // Populate the subcategory dropdown
        options.forEach(subcategory => {
            const option = document.createElement("option");
            option.value = subcategory;
            option.text = subcategory;
            subcategorySelect.add(option);
        });
    }

        document.addEventListener("DOMContentLoaded", function() {
            // Check if there's an alert message set in the PHP variable
            const alertMessage = "<?php echo $alertMessage; ?>";
            if (alertMessage) {
                // Display the alert message
                alert(alertMessage);
            }
        });

        // Event listener to update subcategories when category changes
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("category").addEventListener("change", populateSubcategories);
        });
        
    </script>
    <script>
    $(document).ready(function(){
        // Function to display alert message
        function showAlert(message) {
            alert(message);
        }

        // Function to handle form submission
        $("form").submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            // Submit the form via AJAX
            $.ajax({
                type: "POST",
                url: "", // Set your form submission URL
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    showAlert("Post successfully posted!");
                    // Optionally, you can reset the form after successful submission
                    $('form')[0].reset();
                },
                error: function(xhr, status, error) {
                    showAlert("Error: " + error); // Display error message if submission fails
                }
            });
        });
    });
    </script>
    
</head>
<body>
    <header>
        <!-- Navbar -->
        <nav class="navbar">
            <div class="logo">
                <img src="images/logo.png" alt="Airbnb Logo" style="width: 65%;">
            </div>
                <!-- Header Menu -->
                <ul class="header-menu">
                    <li><a href="homeadmin.php" style="font-weight: 200;">HOME</a></li>
                    <?php if ($status_1 === 'enabled') : ?>
                        <li><a href="viewaboutus.php" style="font-weight: 200;">ABOUT</a></li>
                    <?php endif; ?>
                    <li><a href="usersadmin.php" style="font-weight: 200;">PROFILE</a></li>
                    <li><a href="scammersadmin.php" style="font-weight: 200;">SCAMS</a></li>
                    <li><a href="modusadmin.php" style="font-weight: 200;">MODUS</a></li>
                    <li><a href="admin.php" style="font-weight: 200;">MANAGE</a></li>
                    <li><a href="#" id="logout-btn" style="font-weight: 200; background-color: green;">LOG OUT</a></li>
                </ul>
            
        </nav>
        <hr class="navbar-divider" style="border-radius: 40px;">
        
        <!-- Button "Post a Scam" -->
        <div class="post-scam-button">
            <a href="postscam.php" class="post-scam-btn">POST A SCAM</a>
        </div>
        
    </header>

    <!-- Main content section -->
        <main>
        <div>
            <p class="post-scam-heading">POST A SCAM</p>
            <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width: 97%;margin-bottom: 20px;">
        </div>
        
        <!-- Scam Details Form -->
        <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" id="count_id" name="count_id" value="">
            <!-- Dropdown of Categories -->
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="">Select a category</option>
                <option value="HOME & GARDEN">HOME & GARDEN</option>
                <option value="ENTERTAINMENT">ENTERTAINMENT</option>
                <option value="FAMILY">FAMILY</option>
                <option value="ELECTRONICS">ELECTRONICS</option>
                <option value="HOBBIES">HOBBIES</option>
                <option value="BUSINESS">BUSINESS</option>
            </select><br>

            <!-- Subcategories Dropdown -->
            <label for="subcategory">Subcategory:</label>
            <select name="sub_category" id="subcategory" required>
                <option value="">Please select a category first</option>
            </select><br>

            <!-- Title -->
            <label for="title">Title:</label>
            <input type="text" id="title" name="title"><br>

            <!-- Scammer Name -->
            <label for="scammer_name">Scammer Name:</label>
            <input type="text" id="scammer_name" name="scammer_name"><br>

            <!-- Multiple Image Upload -->
            <label for="images">Upload Multiple Images:</label>
            <input type="file" id="images" name="images[]" multiple><br>

            <!-- Facebook Link -->
            <label for="facebook_link">Facebook Link:</label>
            <input type="text" id="facebook_link" name="fb_link"><br>

            <!-- Scammer's Phone Number -->
            <label for="phone_number">Scammer's Phone Number:</label>
            <input type="text" id="phone_number" name="sc_phone"><br>

            <!-- Scammer's Email -->
            <label for="email">Scammer's Email:</label>
            <input type="email" id="email" name="sc_email"><br>

            <!-- Scammer's Username -->
            <label for="username">Scammer's Username:</label>
            <input type="text" id="username" name="sc_username"><br>

            <!-- Scammer's Bank Name -->
            <label for="bank_name">Scammer's Bank Name:</label>
            <select name="sc_bankname" id="bank_name">
                <!-- Populate with banks from your database -->
                <option value="BDO">BDO</option>
                <option value="BPI">BPI</option>
                <option value="Landbank">Landbank</option>
                <!-- Add more options as needed -->
            </select><br>

            <!-- Scammer's Account Name -->
            <label for="account_name">Scammer's Account Name:</label>
            <input type="text" id="account_name" name="sc_bankacctname"><br>

            <!-- Scammer's Account Number -->
            <label for="account_number">Scammer's Account Number:</label>
            <input type="text" id="account_number" name="sc_bankacctnumber"><br>

            <!-- QR Code Image Upload -->
            <label for="qr_code">Upload QR Code Image:</label>
            <input type="file" id="qr_code" name="qr_img"><br>

            <!-- Description -->
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br>

            <!-- Terms and Conditions -->
            <div class="terms-container" style="display:flex;">
                <input type="checkbox" id="agree" name="agreed_terms" value="agreed" style="width:3%; height:3%;">
                <label for="agree">I agree to the <a href="viewterms.php" target="_blank" style="color:blue">terms and conditions</a></label><br>
            </div><br>

            <!-- Submit Button -->
            <input type="submit" value="Submit">
        </form>

    </main>


   <!-- Footer -->
   <footer>
    <div class="row" style="justify-content: left;">
        <p style="color: #fff; font-size: 22px; margin-left: 0px; margin-bottom: 0px;">All Categories</p>
    </div>
    <div class="row" style="justify-content: left; width: 1400px;">
        <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width:100% ;margin-bottom: 20px;">
    </div>
    <div class="row">
        <div class="footer-content">
            <ul class="category-list">
                <li class="column">
                    <span class="category-name">HOME AND GARDEN</span>
                    <ul class="subcategory-list">
                        <li><a href="subcategories/home/kitchen.html">Furniture</a></li>
                        <li><a href="subcategories/home/gardening.html">Kitchenware</a></li>
                        <li><a href="subcategories/home/gardening.html">Gardening Tools & Supplies</a></li>
                        <li><a href="subcategories/home/gardening.html">Cleaning Materials</a></li>
                        <li><a href="subcategories/home/kitchen.html">Food</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">ENTERTAINMENT</span>
                    <ul class="subcategory-list">
                        <li><a href="subcategories/entertainment/tv.html">TV & Home Theater</a></li>
                        <li><a href="subcategories/entertainment/games.html">Video Games</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">FAMILY</span>
                    <ul class="subcategory-list">
                        <li><a href="subcategories/family/toys.html">Toys & Games</a></li>
                        <li><a href="subcategories/family/clothing.html">Kids' Clothing</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">ELECTRONICS</span>
                    <ul class="subcategory-list">
                        <li><a href="subcategories/electronics/smartphones.html">Smartphones</a></li>
                        <li><a href="subcategories/electronics/laptops.html">Laptops</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">HOBBIES</span>
                    <ul class="subcategory-list">
                        <li><a href="subcategories/hobbies/arts.html">Arts & Crafts</a></li>
                        <li><a href="subcategories/hobbies/music.html">Musical Instruments</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">BUSINESS</span>
                    <ul class="subcategory-list">
                        <li><a href="subcategories/business/office.html">Office Supplies</a></li>
                        <li><a href="subcategories/business/services.html">Business Services</a></li>
                    </ul>
                </li>
                
            </ul>
        </div>
    </div>
    <div>
        <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width: 1420px;margin-bottom: 5px;">
    </div>
    
        <!-- Navbar -->
        <nav class="navbar">
            <div class="row" style="width: 1200px;">
            <li class="column">
                <div class="logo">
                    <img src="images/logo.png" alt="Airbnb Logo" style="width: 65%; margin-bottom: 10px;">
                </div>
            </li>
           <li class="column"></li>

                <!-- Footer Menu -->
            <li class="column" >    
                <ul class="footer-menu">
                    <li><a href="viewprivacy.php" style="font-size: 13px;" >PRIVACY POLICY</a></li>
                    <li><a href="viewterms.php" style="font-size: 13px;">TERMS AND CONDITIONS</a></li>
                    <li><a href="viewcontactus.php" style="font-size: 13px;">CONTACT US</a></li>
                    <li><p style="color: #fff; font-size: 13px; margin-top: 5px;">COPYRIGHT SAGISHI 2024</p></li>

                </ul>
            </li>
            
            </div>        
        </nav>
    
    
</footer>
<script>
        // Generate a random unique ID
    function generateUniqueId() {
        return 'id_' + Math.random().toString(36).substr(2, 9); // Generates a random alphanumeric string
    }

    // Set the unique ID in the count_id input field
    document.getElementById('count_id').value = generateUniqueId();
    </script>
<script>
    document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Log out of your account?")) {
                window.location.href = '/App/home.php';
            }
        });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
        function showAlert() {
            alert("<?php echo $alertMessage; ?>");
        }
    </script>
</body>
</html>
