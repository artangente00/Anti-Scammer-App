<?php
session_start();

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

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the existing status
$status = '';
$result = $conn->query("SELECT status FROM editpage WHERE id = 1");
if ($result === false) {
    die("Query failed: " . $conn->error);
}
if ($row = $result->fetch_assoc()) {
    $status = $row['status'];
}

// Fetch data from the database
$sql = "SELECT user_id, user_role, firstmid_name, last_name, email, country, phone, no_posts, date_registered FROM users";
$result = $conn->query($sql);



$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .settings-btn {
            background: none;
            border: none;
            cursor: pointer;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .settings-btn:hover .dropdown-content {
            display: block;
        }
        .role-button {
            margin-left: 10px;
            padding: 2px 8px;
            border: none;
            border-radius: 4px;
            color: #fff;
        }
        .role-button.admin {
            background-color: green;
        }
        .role-button.disabled {
            background-color: gray;
        }
        .role-button.merged {
            background-color: orange;
        }
        .role-button.user {
            background-color: blue;
        }
        .button-edit {
            color: #201c34;
            padding-top: 8px;
            padding-bottom: 8px;
            padding-left: 5px;
            cursor: pointer;
            text-decoration: none;
            background-color: #f9f9f9;
            display: block;
        }

        .button-edit:hover {
            background-color: #201c34;
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
            <a href="#" class="post-scam-btn">POST A SCAM</a>
        </div>
        
    </header>
    
    

<main>
    <div class="row" style="justify-content: left; width: 100%; margin-left: 20px;">
        <!-- Tab Buttons -->
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'allUsersTable')">All Users</button>
            <button class="tablinks" onclick="openTab(event, 'postsTable')">Posts</button>
            <button class="tablinks" onclick="openTab(event, 'contentTable')">Content</button>
        </div>
    </div>
        
        <!-- Tab Content - All Users -->
<div id="allUsersTable" class="tabcontent">
    <div class="row" style="width: 100%;">
        <!-- Content for All Users Tab -->
        <table border="1" style="width: 100%;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Country</th>
                    <th>Phone Number</th>
                    <th># of Posts</th>
                    <th>Registered Since</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>';
                        echo '<button class="settings-btn" onclick="toggleSettingsMenu(this)" style="margin-right: 5px;">';
                        echo '<i class="fas fa-cog"></i>';
                        echo '</button>';
                        echo '<div class="dropdown-content">';
                        echo '<button onclick="showActivities(\'' . htmlspecialchars($row['firstmid_name'] . ' ' . $row['last_name']) . '\') "><i class="fas fa-exclamation" style="margin-right: 5px;"></i>Activities</button>';
                        echo '<a href="edituser.php?id=' . urlencode($row['user_id']) . '" target="_blank" class="button-edit">';
                        echo '<i class="fas fa-edit" style="margin-right: 5px;"></i>Edit User';
                        echo '</a>';
                        echo '<button onclick="blockUser(' . $row['user_id'] . ')"><i class="fas fa-ban" style="margin-right: 5px;"></i>Block</button>';
                        echo '</div>';
                        echo '<a href="viewuser.php?id=' . urlencode($row['user_id']) . '">' . htmlspecialchars($row['firstmid_name'] . ' ' . $row['last_name']) . '</a>';

                        
                        // Add role indicator
                        $roleClass = '';
                        $roleName = ucfirst($row['user_role']);
                        switch ($row['user_role']) {
                            case 'admin':
                                $roleClass = 'admin';
                                break;
                            case 'disabled':
                                $roleClass = 'disabled';
                                break;
                            case 'merged':
                                $roleClass = 'merged';
                                break;
                            default:
                                $roleClass = 'user';
                        }
                        echo '<button class="role-button ' . $roleClass . '">' . htmlspecialchars($roleName) . '</button>';
                        
                        echo '</td>';
                        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['country']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['no_posts']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['date_registered']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7">No users found</td></tr>';
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

        <!-- Tab Content - Posts -->
        <div id="postsTable" class="tabcontent">
            <!-- Content for Posts Tab -->
            <div class="row" style="width: 100%;">
                <!-- Content for All Users Tab -->
                <table border="1" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Date Posted</th>
                            <th>Creator</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample User Data -->
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>2024-05-01</td>
                            <td>johndoe@example.com</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>2024-04-15</td>
                            <td>janesmith@example.com</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>David Johnson</td>
                            <td>2024-03-10</td>
                            <td>davidjohnson@example.com</td>
                        </tr>
                        <!-- Add more user rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content - Content -->
        <div id="contentTable" class="tabcontent">
            <!-- Content for Content Tab -->
            <div class="row" style="width: 100%;">
                <!-- Content for All Users Tab -->
                <table border="1" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Last Update</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample User Data -->
                        <tr>
                            <td>About Us</td>
                            <td>Enabled</td>
                            <td>2024-05-01</td>
                            <td>
                                <button onclick="window.location.href='viewaboutus.php'"><i class="fa fa-eye" style="color: orange; margin-right: 6px;"></i>View</button>
                                <button onclick="window.location.href='editaboutus.php'"><i class="fa fa-pen" style="color: yellow; margin-right: 6px;"></i>Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Privacy Policy</td>
                            <td>Enabled</td>
                            <td>2024-04-15</td>
                            <td>
                                <button onclick="window.location.href='viewprivacy.php'"><i class="fa fa-eye" style="color: orange; margin-right: 6px;"></i>View</button>
                                <button onclick="window.location.href='editprivacy.php'"><i class="fa fa-pen" style="color: yellow; margin-right: 6px;"></i>Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Terms & Conditions</td>
                            <td>Enabled</td>
                            <td>2024-03-10</td>
                            <td>
                                <button onclick="window.location.href='viewterms.php'"><i class="fa fa-eye" style="color: orange; margin-right: 6px;"></i>View</button>
                                <button onclick="window.location.href='editterms.php'"><i class="fa fa-pen" style="color: yellow; margin-right: 6px;"></i>Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Contact Us</td>
                            <td>Enabled</td>
                            <td>2024-03-10</td>
                            <td>
                                <button onclick="window.location.href='viewcontactus.php'"><i class="fa fa-eye" style="color: orange; margin-right: 6px;"></i>View</button>
                                <button onclick="window.location.href='editcontactus.php'"><i class="fa fa-pen" style="color: yellow; margin-right: 6px;"></i>Edit</button>
                            </td>
                        </tr>
                        <!-- Add more user rows as needed -->
                    </tbody>
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
<script src="js/admin2.js"></script>
<script>



    // Function to block a user
    function blockUser(userId) {
        if (confirm("Are you sure you want to block this user?")) {
            // Send an AJAX request to block the user
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "blockuser.php?id=" + userId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Handle successful response
                    alert("User blocked successfully");
                    // Optionally, you can reload the page or update the UI here
                } else if (xhr.readyState == 4 && xhr.status != 200) {
                    // Handle error response
                    alert("Failed to block user");
                }
            };
            xhr.send();
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
