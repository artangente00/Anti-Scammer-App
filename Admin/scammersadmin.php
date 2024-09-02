<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /home.php");
    exit();
}

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

// Database configuration
//$hostname = 'localhost';
//$username = 'root';
//$password = '';
//$database = 'test';

//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the request
$search_query = isset($_POST['search_query']) ? $conn->real_escape_string($_POST['search_query']) : '';

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

// Close the connection
$conn->close();

// Now $post_ids contains all the post_ids from the posts table

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScamSecure</title>
    <link rel="stylesheet" href="css/home2.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/scammers1.css">
    <style>
    .post-details {
        display: flex;
        align-items: center;
        gap: 20px; /* Space between the category and the post info */
        padding: 10px;
        margin-bottom: 20px;
        margin-top:0px;
        width: 100%; /* Ensure the container takes the full width */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional: Add shadow for better visual separation */
        border-radius: 10px; /* Optional: Add rounded corners to the container */
        margin-left: 50px;
    }

    .category {
        position: relative; /* Ensure positioning context for the label */
        width: 300px; /* Fixed width */
    }

    .category img {
        width: 100%; /* Full width of the category container */
        height: 180px; /* Fixed height */
        object-fit: cover; /* Ensure the image covers the entire space */
        border-radius: 10px; /* Optional: Add rounded corners to the image */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional: Add shadow effect */
    }

    .category-label {
        position: absolute;
        bottom: 10px; /* Adjust positioning from bottom */
        left: 0;
        right: 0;
        padding: 5px;
        background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
        color: #fff;
        font-size: 14px;
        text-align: center;
        border-radius: 0 0 10px 10px; /* Match the border radius of the image */
    }

    .post-info {
        display: flex;
        flex-direction: column;
        margin-left: 20px; /* Space between the image and the text */
        flex: 1; /* Take up the remaining space */
    }

    .post-info h1 {
        margin: 0 0 10px 0; /* Add some space below the title */
        font-size: 26px; /* Adjust title font size */
    }

    .post-info p {
        margin: 2px 0; /* Adjust spacing between paragraphs */
        font-size: 18px;
    }

    #search-results {
        display: none;
        width: 100%; /* Ensure full width */
        align-items: center;

    }
    #results {
        display: none;
        width: 100%; /* Ensure full width */
        align-items: center;
    }
    .search-button{
        margin-top: 5px;
        border-radius: 20px; /* Button border radius */

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

    <!-- Hero section -->
    <div class="hero">
        <div class="search-bar">
            <input type="text" id="search-input" class="search-input" placeholder="SEARCH FOR SCAM/SCAMMER" style="border-radius: 20px; font-weight: 300; font-size: 18px; width: 25%; text-align: center;">
            <p>INPUT NAME, NUMBER, BANK INFO, DIGITAL WALLET INFO</p>
            <button id="search-button" class="search-button" style="border-radius: 20px; ">SEARCH</button>
        </div>
    </div> 

    <!-- Main content section -->
    <main>
    <div id="results">
        <div class="row" style="justify-content: left;">
            <p style="color: #fff; font-size: 22px; margin-left: 25px; margin-bottom: 0px;">Results:</p>
        </div>
        <div class="row" style="justify-content: left; width: 100%;">
            <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width:97%; margin-bottom: 20px;">
        </div>
    </div>
       
        <div id="search-results" class="row">
            <!-- Search results will be displayed here -->
        </div>
        

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

                <?php if ($status_3 == 'enabled') : ?>
                    <li><a href="viewprivacy.php" style="font-size: 13px;" >PRIVACY POLICY</a></li>
                <?php endif; ?>
                <?php if ($status_4 == 'enabled') : ?>
                    <li><a href="viewterms.php" style="font-size: 13px;">TERMS AND CONDITIONS</a></li>
                <?php endif; ?>
                <?php if ($status_2 == 'enabled') : ?>
                    <li><a href="viewcontactus.php" style="font-size: 13px;">CONTACT US</a></li>
                <?php endif; ?>                        
                    <li><p style="color: #fff; font-size: 13px; margin-top: 5px;">COPYRIGHT SAGISHI 2024</p></li>

                </ul>
            </li>
            
            </div>        
        </nav>
    
    
</footer>
    <!-- Link to external JavaScript file -->
    <script src="js/home.js"></script>
    <script>
        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Log out of your account?")) {
                window.location.href = '/App/home.php';
            }
        });

        document.getElementById('search-button').addEventListener('click', function() {
            var searchInput = document.getElementById('search-input').value;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'search.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var results = JSON.parse(xhr.responseText);
                    displaySearchResults(results);
                    
                    // Show the results section
                    document.getElementById('results').style.display = 'block';
                    document.getElementById('search-results').style.display = 'block';
                }
            };
            xhr.send('search_query=' + encodeURIComponent(searchInput));
        });

        function displaySearchResults(results) {
        console.log(results); // Log the results to inspect the structure
        var resultsContainer = document.getElementById('search-results');
        resultsContainer.innerHTML = '';

        if (results.length === 0) {
            resultsContainer.innerHTML = '<p style="color:white;">No results found.</p>';
            return;
        }

        results.forEach(function(result) {
            console.log(result); // Log each result object to inspect its structure
            var resultElement = document.createElement('div');
            resultElement.className = 'post-container';

            var postDetails = `
            <div class="post-details">
                <div class="category">
                    <a href="posts.php?id=${result.post_id}" class="view-details">
                        <img src="${result.image_url ? result.image_url : 'default.jpg'}" alt="Post Image">
                        <p class="category-label">${result.category}</p>
                    
                </div>
                <div class="post-info">
                    <h1>${result.title}</h1>
                    <p>Scammer Name: ${result.scammer_name}</p>
                    <p>Phone: ${result.sc_phone}</p>
                    <p>Date Posted: ${result.date_posted}</p>
                    <p>Posted by: @${result.user_name}</p>
                    </a>
                </div>
            </div>
            `;

            resultElement.innerHTML = postDetails;
            resultsContainer.appendChild(resultElement);
        });
    }

    // Example AJAX call
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'search.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                var results = JSON.parse(xhr.responseText);
                console.log(results); // Log the parsed results
                displaySearchResults(results);
            } catch (e) {
                console.error('Failed to parse JSON response:', e);
            }
        } else {
            console.error('Request failed with status:', xhr.status);
        }
    };
    xhr.send();

    </script>

</body>
</html>
