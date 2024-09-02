<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /home.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);



// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

// Database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the post ID from the URL parameter
$post_id = $_GET['id'];

// Query the database to fetch the details of the post with the given ID
$query = "SELECT * FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if a post with the given ID exists
if ($result->num_rows === 0) {
    echo "Post not found.";
    exit();
}

// Fetch the post details
$post = $result->fetch_assoc();





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
// Close the connection to free up resources
$conn->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit My Post</title>
    <link rel="stylesheet" href="css/home2.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Link to Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Link to intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
            <p class="post-scam-heading">EDIT POST</p>
            <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width: 97%;margin-bottom: 20px;">
        </div>
        <!-- Form for Updating Post -->
<form action="updatepost.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

    <!-- Category -->
    <label for="category">Category:</label>
    <input type="text" id="category" name="category_display" value="<?php echo htmlspecialchars($post['category']); ?>" readonly>
    <input type="hidden" name="category" value="<?php echo htmlspecialchars($post['category']); ?>"><br>

    <!-- Subcategory -->
    <label for="subcategory">Subcategory:</label>
    <input type="text" id="subcategory" name="subcategory_display" value="<?php echo htmlspecialchars($post['sub_category']); ?>" readonly>
    <input type="hidden" name="sub_category" value="<?php echo htmlspecialchars($post['sub_category']); ?>"><br>


    <!-- Title -->
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br>

    <!-- Scammer Name -->
    <label for="scammer_name">Scammer Name:</label>
    <input type="text" id="scammer_name" name="scammer_name" value="<?php echo htmlspecialchars($post['scammer_name']); ?>" required><br>


    <!-- Facebook Link -->
    <label for="facebook_link">Facebook Link:</label>
    <input type="text" id="facebook_link" name="fb_link" value="<?php echo htmlspecialchars($post['fb_link']); ?>" required><br>

    <!-- Scammer's Phone Number -->
    <label for="phone_number">Scammer's Phone Number:</label>
    <input type="text" id="phone_number" name="sc_phone" value="<?php echo htmlspecialchars($post['sc_phone']); ?>" required><br>

    <!-- Description -->
    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($post['description']); ?></textarea><br>

    <!-- Example for other fields such as bank name, account number, email, username, etc. -->
    <label for="sc_email">Email:</label>
    <input type="email" id="sc_email" name="sc_email" value="<?php echo htmlspecialchars($post['sc_email']); ?>" required><br>

    <label for="sc_bankname">E-Wallet:</label>
    <input type="text" id="sc_bankname" name="sc_bankname" value="<?php echo htmlspecialchars($post['sc_bankname']); ?>" required><br>

    <label for="sc_bankacctnumber">Bank Info:</label>
    <input type="text" id="sc_bankacctnumber" name="sc_bankacctnumber" value="<?php echo htmlspecialchars($post['sc_bankacctnumber']); ?>" required><br>

    <label for="sc_username">TG Username:</label>
    <input type="text" id="sc_username" name="sc_username" value="<?php echo htmlspecialchars($post['sc_username']); ?>" required><br>

    <!-- Submit Button -->
    <button type="submit">Save Changes</button>
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
    document.getElementById('category').addEventListener('change', function() {
        var category = this.value;
        var subcategorySelect = document.getElementById('subcategory');
        subcategorySelect.innerHTML = '<option value="">Please select a category first</option>';
        
        var subcategories = {
            "HOME & GARDEN": ["Furniture", "Gardening", "Decor"],
            "ENTERTAINMENT": ["Movies", "Music", "Games"],
            "FAMILY": ["Parenting", "Relationships", "Home Life"],
            "ELECTRONICS": ["Computers", "Mobile Phones", "Accessories"],
            "HOBBIES": ["Crafts", "Collecting", "Sports"],
            "BUSINESS": ["Finance", "Marketing", "Management"]
        };

        if (category in subcategories) {
            subcategories[category].forEach(function(subcat) {
                var option = document.createElement('option');
                option.value = subcat;
                option.textContent = subcat;
                subcategorySelect.appendChild(option);
            });
        }
    });
</script>
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
