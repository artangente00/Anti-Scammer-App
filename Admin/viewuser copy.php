<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /App/home.html");
    exit();
}

// Check if user ID is passed in the URL
if (!isset($_GET['id'])) {
    // Redirect to the user list page if no user ID is provided
    header("Location: admin.php");
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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User's Profile</title>
    <link rel="stylesheet" href="css/home.css">
    <style>

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

        table {
            width: 70%;
            border-collapse: separate;
            border-spacing: 0 5px; /* Adds space between rows */
            height: 80vh;
        }
        th, td {
            padding: 5px;
            text-align: center;
            vertical-align: top;
            font-size: 18px;
        }
        th {
            padding-right: 0px; /* Adds space between columns */
        }
        .button-blocked { 
            background-color: red; color: white; 
        }
        .button-edit { 
            background-color: blue; color: white; 
        }
        .button-activities { 
            background-color: green; color: white; 
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
    
    

    <main>
        
       <!-- Tab Content - All Users -->
       <div id="aboutme">
            <div class="row">
                <h1><?php echo htmlspecialchars($user['firstmid_name'] . ' ' . $user['last_name']); ?>'s Profile</h1>
            </div>
            <div class="row">
                <button onclick="blockUser()" class="button-blocked">Block User</button>
                <a href="edituser.php?id=<?php echo htmlspecialchars($user['user_id']); ?>" target="_blank"><button class="button-edit">Edit User</button></a>
                <button onclick="viewUserActivities()" class="button-activities">User Activities</button>
            </div>
            <div class="row">
                <table>
                    <tr>
                        <th>Username:</th>
                        <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                    </tr>
                    <tr>
                        <th>First & Middle Name:</th>
                        <td><?php echo htmlspecialchars($user['firstmid_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Last Name:</th>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Role:</th>
                        <td><?php echo htmlspecialchars($user['user_role']); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><?php echo htmlspecialchars($user['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Birthday:</th>
                        <td><?php echo htmlspecialchars($user['birthday']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number:</th>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    </tr>
                    <tr>
                        <th>Country:</th>
                        <td><?php echo htmlspecialchars($user['country']); ?></td>
                    </tr>
                    <tr>
                        <th>Valid ID:</th>
                        <td>
                            <img src="/App/uploads/<?php echo htmlspecialchars($user['id_image']); ?>" alt="Valid ID" style="width:300px;height:200px;">
                        </td>
                    </tr>
                </table>
            </div>
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
                    <li><a href="home.html" style="font-size: 13px;" >PRIVACY POLICY</a></li>
                    <li><a href="aboutus.html" style="font-size: 13px;">TERMS AND CONDITIONS</a></li>
                    <li><a href="contactus.html" style="font-size: 13px;">CONTACT US</a></li>
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
                window.location.href = '/App/home.html';
            }
        });
</script>

</body>
</html>
