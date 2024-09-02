<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /App/home.php");
    exit();
}

// Database configuration
//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

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

// Fetch data from the database for users
$sql = "SELECT user_id, user_role, status, firstmid_name, last_name, email, country, phone, no_posts, date_registered FROM users";
$user_result = $conn->query($sql);
if ($user_result === false) {
    die("Query failed: " . $conn->error);
}
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
        .status-button {
            margin-left: 10px;
            padding: 2px 8px;
            border: none;
            border-radius: 4px;
            color: #fff;
        }
        .status-button.unverified {
            background-color: orange;
        }
        .status-button.verified {
            background-color: green;
        }
        .status-button.blocked {
            background-color: red;
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
        .role-button.None {
            background-color: gray;
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
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            fetch('fetch_posts.php')
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.querySelector('#postsTable tbody');
                    tableBody.innerHTML = ''; // Clear any existing rows
                    data.forEach((row, index) => {
                        let tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${row.title}</td>
                            <td>${row.date_posted}</td>
                            <td>${row.email}</td>
                        `;
                        tableBody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
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
                    if ($user_result->num_rows > 0) {
                        // Output data of each row
                        while($row = $user_result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>';
                            echo '<button class="settings-btn" onclick="toggleSettingsMenu(this)" style="margin-right: 5px;">';
                            echo '<i class="fas fa-cog"></i>';
                            echo '</button>';
                            echo '<div class="dropdown-content">';
                            echo '<button onclick="showActivities(\'' . htmlspecialchars($row['firstmid_name'] . ' ' . $row['last_name']) . '\') " ><i class="fas fa-exclamation" style="margin-right: 5px;"></i>Activities</button>';
                            echo '<a href="edituser.php?id=' . urlencode($row['user_id']) . '" target="_blank" class="button-edit" ">';
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
                                case 'None':
                                    $roleClass = 'None';
                                    break;
                                default:
                                    $roleClass = 'user';
                            }
                            echo '<button class="role-button ' . $roleClass . '">' . htmlspecialchars($roleName) . '</button>';

                            // Add status indicator
                            $statusClass = '';
                            $statusName = ucfirst($row['status']);
                            switch ($row['status']) {
                                case 'unverified':
                                    $statusClass = 'unverified';
                                    break;
                                case 'verified':
                                    $statusClass = 'verified';
                                    break;
                                case 'blocked':
                                    $statusClass = 'blocked';
                                    break;
                                default:
                                    $statusClass = '';
                            }
                            echo '<button class="status-button ' . $statusClass . '">' . htmlspecialchars($statusName) . '</button>';

                            echo '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['country']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['no_posts']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['date_registered']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6">No users found.</td></tr>';
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>

        <!-- Tab Content - Posts -->
        <div id="postsTable" class="tabcontent">
        <div class="row" style="width: 100%;">
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
                    <!-- Rows will be inserted here dynamically by JavaScript -->
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
    document.addEventListener('DOMContentLoaded', () => {
        const postsContainer = document.getElementById('postsTable').querySelector('tbody');
        const category = document.querySelector('h1').dataset.category;
        const subcategoryDropdown = document.getElementById('subcategory-dropdown');

        console.log(`Fetching posts for category: ${category}`); // Debugging line

        fetchPosts(category, '');

        subcategoryDropdown.addEventListener('change', () => {
            const subcategory = subcategoryDropdown.value;
            fetchPosts(category, subcategory);
        });

        function fetchPosts(category, subcategory) {
            // Clear previous posts
            postsContainer.innerHTML = '';

            // Build the URL with the category and subcategory
            let url = `/App/users/get-posts.php?category=${category}`;
            if (subcategory) {
                url += `&subcategory=${subcategory}`;
            }

            // Fetch posts from the server
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(posts => {
                    console.log(posts); // Debugging line
                    if (posts.length > 0) {
                        posts.forEach((post, index) => {
                            const postRow = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${post.title}</td>
                                    <td>${post.date_posted}</td>
                                    <td>${post.email}</td>
                                </tr>
                            `;
                            postsContainer.innerHTML += postRow;
                        });
                    } else {
                        postsContainer.innerHTML = '<tr><td colspan="4">No posts found for this category.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching posts:', error);
                    postsContainer.innerHTML = '<tr><td colspan="4">There was an error fetching the posts. Please try again later.</td></tr>';
                });
        }
    });
</script>


<script>
    // Function to open the specified tab
    function openTab(evt, tabName) {
        // Get all elements with class "tabcontent" and hide them
        var tabcontents = document.getElementsByClassName("tabcontent");
        for (var i = 0; i < tabcontents.length; i++) {
            tabcontents[i].style.display = "none";
        }

        // Get all elements with class "tablinks" and remove the "active" class
        var tablinks = document.getElementsByClassName("tablinks");
        for (var i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        // Show the current tab content and add an "active" class to the clicked tab button
        document.getElementById(tabName).style.display = "block";
        if (evt) {
            evt.currentTarget.classList.add("active");
        }
    }

    // Open the 'allUserTable' tab content and add the "active" class to its corresponding tab button when the page loads
    document.addEventListener("DOMContentLoaded", function() {
        // Show the 'allUserTable' tab content
        document.getElementById('allUsersTable').style.display = "block";
        
        // Add the "active" class to the corresponding tab button
        var defaultTabButton = document.querySelector(".tablinks[data-tab='allUsersTable']");
        if (defaultTabButton) {
            defaultTabButton.classList.add("active");
        }
    });

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
                window.location.href = '/App/home.php';
            }
    });
</script>



</body>
</html>
