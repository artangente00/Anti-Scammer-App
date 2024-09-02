<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: /App/home.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScamSecure</title>
    <link rel="stylesheet" href="css/home2.css">
    <!-- Include Froala Editor CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.2.2/css/froala_style.min.css">
</head>
   
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
                    <li><a href="aboutusadmin.php" style="font-weight: 200;">ABOUT</a></li>
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
            <p style="color: #fff; font-size: 28px; font-weight: 300; margin-left: 15px;">Title:About</p>
            <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width: 97%;margin-bottom: 20px;">
        </div>
        <div class="content-edit">
            <div class="row" style="width: 100%;">
                <form id="contentForm">
        
                    <!-- Description input -->
                    <div class="row" style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 5px;">
                        <label for="description" style="width: 120px;">Description:</label>
                    </div>
                    <div class="row" style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 5px;">
                        <textarea id="description" name="description" rows="4" style="flex: 1; height: 20vh; margin-left: 0px; border-radius: 20px;" required></textarea>
                    </div>
        
                    <!-- Enable/Disable checkbox -->
                    <div class="row" style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 5px;">
                        <label for="status" style="width: 120px;">Status:</label>
                    </div>
                    <div class="row" style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 5px;">
                        <div class="status-options">
                            <input type="checkbox" id="enabled" name="status" value="enabled" checked>
                            <label for="enabled">Enabled</label>
                            <input type="checkbox" id="disabled" name="status" value="disabled">
                            <label for="disabled">Disabled</label>
                        </div>
                    </div>
        
                    <!-- Rich Text Editor (Froala Editor) -->
                    <div class="row" style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 5px;">
                        <label for="content" style="width: 120px;">Content:</label>
                    </div>
                    <div class="row" style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 8px;">
                        <textarea id="content" name="content" style="flex: 1; margin-left: 0px; height: 30vh;"></textarea>
                    </div>
        
                    <!-- Save and Cancel buttons -->
                    <div class="row" style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 5px;">
                        <button type="submit" onclick="saveChanges()" style="margin-right: 10px;">Save Changes</button>
                        <button type="button" onclick="cancel()">Cancel</button>
                    </div>
                </form>
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
    <!-- Link to external JavaScript file -->
    <script src="home.js"></script>
    <!-- Include Froala Editor JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.2.2/js/froala_editor.pkgd.min.js"></script>
    <!-- Script for initializing Froala Editor -->
    <script>
        // Initialize Froala Editor on the 'content' textarea
        new FroalaEditor('#content');

        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Log out of your account?")) {
                window.location.href = '/App/home.php';
            }
        });
    </script>

</body>
</html>
