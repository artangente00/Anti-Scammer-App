<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /App/home.html");
    exit();
}

// Database configuration
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $content = $_POST['content'];
    $description = $_POST['description'];

    // Save the content to the database
    $stmt = $conn->prepare("UPDATE editpage SET content = ?, status = ?, description = ? WHERE id = 1");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('sss', $content, $status, $description);
    $stmt->execute();
    if ($stmt->error) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // Redirect to viewaboutus.php
    header("Location: viewaboutus.php");
    exit();
}

// Fetch the existing content
$result = $conn->query("SELECT content, status, description FROM editpage WHERE id = 1");
if ($result === false) {
    die("Query failed: " . $conn->error);
}
$row = $result->fetch_assoc();
$content = $row['content'];
$status = $row['status'];
$description = $row['description'];


$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About Us</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Link to Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Link to intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <!-- Link to Captcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
    .button-cancel:hover {
            background-color: #007bff;
        }
    </style>
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
                <?php if ($status === 'enabled') : ?>
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
            <a href="#" class="post-scam-btn">POST A SCAM</a>
        </div>
        
    </header>

    <!-- Main content section -->
    <main>
    <div>
            <p style="color: #fff; font-size: 28px; font-weight: 300; margin-left: 15px;">Title:About Us</p>
            <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width: 97%;margin-bottom: 20px;">
    </div>
    
    <form method="POST" action="editaboutus.php">
        <div class="row" style="justify-content: left; width: 100%">
            <textarea rows="4" name="description" style="width: 97%; margin-left: 10px; border-radius:10px;"><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        <div class="row" style="justify-content: left; width: 15%; padding-top:10px; display: block; padding-bottom:10px; margin-left: 10px; margin-top: 10px; background-color:white;">
            <div class="row" style="width:100%;">
                <label style="color: black; font-size: 16px; margin-right: auto; margin-left: 10px; display: block;">Status:</label>
            </div>
            <div class="row" style="width:100%; display: flex; margin-top: 10px;">
                <label style="color: black; font-size: 16px; margin-right: auto; display: block;">
                    <input type="radio" name="status" value="disabled" <?php echo ($status === 'disabled') ? 'checked' : ''; ?>> Disabled
                </label>
                <label style="color: black; font-size: 16px; display: block; margin-right: auto;">
                    <input type="radio" name="status" value="enabled" <?php echo ($status === 'enabled') ? 'checked' : ''; ?>> Enabled
                </label>
            </div>
        </div>
        
        <div class="row" style="justify-content: left; width: 100%; margin-top: 20px;">
            <textarea id="froala-editor" name="content" style="width: 97%; margin-left: 10px;"><?php echo htmlspecialchars($content); ?></textarea>
        </div>
        
        <div class="row" style="width:100%; margin-top: 20px; display: flex; justify-content: left; margin-left: 10px;">
            <button type="submit" class="button button-save" style="width:15%;">Save Changes</button>
            <button type="button" onclick="window.location.href='viewaboutus.php'" class="button button-cancel">Cancel</button>
        </div>
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
    document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Log out of your account?")) {
                window.location.href = '/App/home.html';
            }
        });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include Froala Editor JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.10/js/froala_editor.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#froala-editor').froalaEditor();
        });
    </script>

</body>
</html>
