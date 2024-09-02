<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /App/home.php");
    exit();
}

// Check if user ID is passed in the URL
if (!isset($_GET['id'])) {
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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $user_role = $_POST['user_role'];
    $status = $_POST['status'];
    $firstmid_name = $_POST['firstmid_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $phone = $_POST['phone'];

    // Update user in the database
    $stmt = $conn->prepare("UPDATE users SET user_name=?, user_role=?, status=?, firstmid_name=?, last_name=?, email=?, birthday=?, phone=? WHERE user_id=?");
    $stmt->bind_param("ssssssssi", $user_name, $user_role, $status, $firstmid_name, $last_name, $email, $birthday, $phone, $user_id);

    if ($stmt->execute()) {
        echo '<script>alert("User updated successfully!");</script>';
        echo '<script>window.location.href = "viewuser.php?id=' . urlencode($user_id) . '";</script>';
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
} else {
    // Fetch user data to pre-fill the form
    $result = $conn->query("SELECT * FROM users WHERE user_id = $user_id");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User's Profile</title>
    <link rel="stylesheet" href="css/home2.css">
    <style>
    #aboutme {
        width: 60%;
        margin: auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    form {
        margin-top: 20px;
    }

    table {
        width: 100%;
    }

    th {
        text-align: center;
        padding: 10px;
    }

    h1 {
        text-align: center;
        padding: 10px;
    }

    td {
        padding: 10px;
        text-align: center;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    select {
        width: calc(100% - 20px);
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        padding: 10px 20px;
        margin-top: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        
    }

    button[type="submit"] {
        background-color: #28a745;
    }

    button[type="button"] {
        background-color: #dc3545;
        margin-left: 10px;
    }

    button:hover {
        background-color: #0056b3;
    }

    button[type="submit"]:hover {
        background-color: #218838;
    }

    button[type="button"]:hover {
        background-color: #c82333;
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
    
    

    <main>
        
        <!-- Tab Content - All Users -->
<div id="aboutme">
    <h1>Edit <?php echo htmlspecialchars($user['firstmid_name'] . ' ' . $user['last_name']); ?>'s Profile</h1>
    <form id="editForm" method="POST" action="edituser.php?id=<?php echo htmlspecialchars($user['user_id']); ?>">
        <table>
            <tr>
                <th>Username:</th>
                <td><input type="text" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>"></td>
            </tr>
            <tr>
                <th>User Role:</th>
                <td>
                    <select name="user_role">
                        <option value="none"<?php if ($user['user_role'] === 'None') echo ' selected'; ?>>None</option>
                        <option value="admin"<?php if ($user['user_role'] === 'admin') echo ' selected'; ?>>Admin</option>
                        <option value="user"<?php if ($user['user_role'] === 'user') echo ' selected'; ?>>User</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>
                    <select name="status">
                        <option value="unverified"<?php if ($user['status'] === 'unverified') echo ' selected'; ?>>Unverified</option>
                        <option value="verified"<?php if ($user['status'] === 'verified') echo ' selected'; ?>>Verified</option>
                        <option value="blocked"<?php if ($user['status'] === 'blocked') echo ' selected'; ?>>Blocked</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>First & Middle Name:</th>
                <td><input type="text" name="firstmid_name" value="<?php echo htmlspecialchars($user['firstmid_name']); ?>"></td>
            </tr>
            <tr>
                <th>Last Name:</th>
                <td><input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>"></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"></td>
            </tr>
            <tr>
                <th>Birthday:</th>
                <td><input type="date" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>"></td>
            </tr>
            <tr>
                <th>Phone Number:</th>
                <td><input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Save</button>
                    <button type="button" onclick="window.location.href='viewuser.php?id=<?php echo htmlspecialchars($user['user_id']); ?>'">Cancel</button>
                </td>
            </tr>
        </table>
    </form>
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

// Function to handle form submission
function handleFormSubmission(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        // Perform AJAX request to submit the form data
        var formData = new FormData(document.getElementById('editForm'));
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'edituser.php?id=<?php echo htmlspecialchars($user['user_id']); ?>', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('User Edited Successfully'); // Display pop-up alert on success
                window.location.href = 'viewuser.php?id=<?php echo htmlspecialchars($user['user_id']); ?>'; // Redirect to viewuser.php
            } else {
                alert('Error editing user. Please try again later.'); // Display error alert on failure
            }
        };
        xhr.send(formData);
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
        
    document.getElementById('saveButton').addEventListener('click', function() {
    alert('User details updated successfully.');
    });
</script>

</body>
</html>
