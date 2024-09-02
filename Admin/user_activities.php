<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /home.php");
    exit();
}

// Check if user ID is passed in the URL
if (!isset($_GET['id'])) {
    // Redirect to the user list page if no user ID is provided
    header("Location: /App/home.php");
    exit();
}

$user_id = $_GET['id'];

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user activities
$sql_activities = "SELECT activity_type, description, timestamp FROM user_activities WHERE user_id = ? ORDER BY timestamp DESC";
$stmt_activities = $conn->prepare($sql_activities);
$stmt_activities->bind_param("i", $user_id);
$stmt_activities->execute();
$result_activities = $stmt_activities->get_result();

$activities = [];
while ($row = $result_activities->fetch_assoc()) {
    $activities[] = $row;
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

// Fetch the existing status
$status = '';
$result = $conn->query("SELECT status FROM editpage WHERE id = 1");
if ($result === false) {
    die("Query failed: " . $conn->error);
}
if ($row = $result->fetch_assoc()) {
    $status = $row['status'];
}

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // No user found, redirect to the user list page
    header("Location: usersadmin.php");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Determine status background color
$status_color = '';
if ($user['status'] === 'unverified') {
    $status_color = 'color: orange;';
} elseif ($user['status'] === 'verified') {
    $status_color = 'color: green;';
} elseif ($user['status'] === 'blocked') {
    $status_color = 'color: red;';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User's Profile</title>
    <link rel="stylesheet" href="css/home2.css">
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    main {
        background-color: #201c34;
        padding: 20px;
        color: white;
        margin: 0 auto;
        max-width: 1200px;
    }

    h1 {
        margin-left: 10px;
        color: white;
    }

    a {
        color: white;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
        
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #201c34;
    }

    th, td {
        padding: 10px;
        border: 1px solid #555;
    }

    th {
        background-color: #444;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:nth-child(odd) {
        background-color: #e9e9e9;
    }

    tr:hover {
        background-color: #ddd;
    }

    .no-activities {
        color: white;
    }

    #aboutme {
        color: white; /* Ensure all text in aboutme section is white */
        height: 100vh;
    }
    
    .editForm {
        display: none;
    }

    .editForm input[type="text"],
    .editForm input[type="date"] {
        background-color: #444; /* Dark input background */
        color: white; /* White text in inputs */
        border: 1px solid #555; /* Border color */
    }

    button {
        background-color: #555; /* Dark button background */
        color: white; /* White text on buttons */
        border: none;
        padding: 5px 10px;
        margin-left: 5px;
    }

    button:hover {
        background-color: #666; /* Slightly lighter on hover */
    }

    .editForm button[type="submit"] {
        background-color: #28a745; /* Green background for Save button */
    }

    .editForm button[type="button"] {
        background-color: #dc3545; /* Red background for Cancel button */
    }

    #aboutme table {
        width: 70%;
        border-collapse: separate;
        border-spacing: 0 5px; /* Adds space between rows */
        height: 80vh;
    }

    #aboutme th, #aboutme td {
        padding: 5px;
        text-align: center;
        vertical-align: top;
        font-size: 18px;
    }

    #aboutme th {
        padding-right: 0px; /* Adds space between columns */
    }

    .button-blocked { 
        background-color: red; 
        color: white; 
    }

    .button-edit { 
        background-color: blue; 
        color: white; 
    }

    .button-activities { 
        background-color: green; 
        color: white; 
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
    
    

    <main style="background-color: #201c34;; padding: 20px;">
    <h1 style="color: white; margin-left: 10px;">
        <?php echo htmlspecialchars($user['firstmid_name'] . ' ' . $user['last_name']); ?>'s Activities
    </h1>
    <div style="margin-left: 10px; margin-bottom: 20px;">
        <a href="viewuser.php?id=<?php echo htmlspecialchars($user_id); ?>" style="color: #00f; text-decoration: none;">Back to Profile</a>
    </div>
    <div style="margin-left: 10px;">
        <?php
        if (count($activities) > 0) {
            echo "<table style='width: 100%; border-collapse: collapse;'>";
            echo "<thead>";
            echo "<tr style='background-color: #201c34; color: white;'>";
            echo "<th style='padding: 10px; border: 1px solid red;background-color: #201c34;'>Activity Type</th>";
            echo "<th style='padding: 10px; border: 1px solid red;background-color: #201c34;'>Description</th>";
            echo "<th style='padding: 10px; border: 1px solid red;background-color: #201c34;'>Timestamp</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($activities as $activity) {
                echo "<tr style='background-color: #201c34;'>";
                echo "<td style='padding: 10px; border: 1px solid red;text-align: center;'>" . htmlspecialchars($activity['activity_type']) . "</td>";
                echo "<td style='padding: 10px; border: 1px solid red;text-align: center;'>" . htmlspecialchars($activity['description']) . "</td>";
                echo "<td style='padding: 10px; border: 1px solid red;text-align: center;'>" . htmlspecialchars($activity['timestamp']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<div style='color: white;'>No activities found.</div>";
        }
        ?>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/admin1.js"></script>
<script>
    // Function to block the user
    function blockUser() {
        if (confirm("Are you sure you want to block this user?")) {
            // Create an XMLHttpRequest object
            var xhttp = new XMLHttpRequest();
            
            // Define the request URL and method
            var url = "blockuser.php?id=<?php echo htmlspecialchars($user['user_id']); ?>"; // Replace "blockuser.php" with your PHP script
            var method = "POST";
            
            // Configure the request
            xhttp.open(method, url, true);
            
            // Set the request header
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            
            // Define what happens on successful data submission
            xhttp.onload = function() {
                if (xhttp.status === 200) {
                    alert("User has been blocked successfully.");
                    // Here you can update the UI or perform any additional actions
                } else {
                    alert("Error blocking user. Please try again later.");
                }
            };
            
            // Send the request with data (if any)
            xhttp.send();
        }
    }

    // Function to switch between tabs
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Function to enable editing of specific detail
    function editDetail(detailId) {
        var detailSpan = document.getElementById(detailId);
        var newValue = prompt("Enter new value for " + detailId + ":");
        if (newValue !== null && newValue !== "") {
            detailSpan.textContent = newValue;
            // Here you can add logic to update the backend with the new value
        }
    }

    document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Log out of your account?")) {
                window.location.href = '/App/home.php';
            }
        });
</script>

</body>
</html>
