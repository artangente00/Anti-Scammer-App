<?php


// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

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

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAGISHI</title>
    <link rel="stylesheet" href="css/home2.css">
     <!-- Link to Flatpickr CSS -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
     <!-- Link to intl-tel-input CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
     <!-- Link to Captcha -->
     <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        color:white;
    }

    .post-info p {
        margin: 2px 0; /* Adjust spacing between paragraphs */
        font-size: 18px;
        color:white;
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
    @media screen and (max-width: 600px) {
    .popup-content {
        width: 90%;
    }
    }
    </style>
     
</head>
<body>
    <header>
        <!-- Navbar -->
        <nav class="navbar">
            <div class="logo">
                <img src="images/Logo.png" alt="Airbnb Logo" style="width:30%;">
            </div>
                <!-- Header Menu -->
                <ul class="header-menu">
                <li><a href="home.php" style="font-weight: 200;">HOME</a></li>
                <?php if ($status_1 === 'enabled') : ?>
                        <li><a href="viewaboutus.php" style="font-weight: 200;">ABOUT</a></li>
                    <?php endif; ?>
                    <li><a href="scammers.php" style="font-weight: 200;">SCAMS</a></li>
                    <li><a href="modus.php" style="font-weight: 200;">MODUS</a></li>
                    <li><a href="" id="signup-link" style="font-weight: 200;">SIGN UP</a></li>
                </ul>
            
        </nav>
        <hr class="navbar-divider" style="border-radius: 40px;">
        <!-- Button "Post a Scam" -->
        <div class="post-scam-button">
            <a href="#" onclick="showAlert()" class="post-scam-btn">POST A SCAM</a>
        </div>
        
    </header>



    <div id="signup-popup" style="display: none;">
    <div class="popup-content" style="position: relative; max-height: 90vh; overflow-y: auto; background-color: #201c34; padding: 20px; border-radius: 30px;">
        <button type="button" onclick="closeSignupPopup()" style="position: absolute; top: 10px; right: 10px; font-size: 18px; color: white; background-color: green; border: none; cursor: pointer;">&times;</button>
        <div class="row" style="display: flex; justify-content: space-evenly; width: 100%; margin-top: 40px; margin-bottom: 20px;">
            <div class="column" style="width: 100%;">
                <div class="row" style="display: flex; justify-content: center; width: 100%;">
                    <h2 style="color: white;">SIGN IN</h2>
                </div>
                <div class="row" style="display: flex; border-right: 1px solid red; padding-right: 30px; justify-content: center; width: 100%; margin-top: 10px;">
                    <form id="signin-form" action="signin.php" method="post">
                        <label for="email" style="color: white;">Email:</label>
                        <input type="email" id="email" name="email" required><br><br>
                        <label for="password" style="color: white;">Password:</label>
                        <input type="password" id="password" name="password" required><br><br>
                        <button type="submit">Sign In</button>
                    </form>
                </div>
            </div>
            <div class="column" style="width: 100%;">   
                <div class="row" style="width: 100%;">
                    <h2 style="color: white;">SIGN UP</h2>
                </div>
                <div class="row" style="width: 100%; margin-top: 10px;">
                    <form id="signup-form" action="signup.php" method="post" enctype="multipart/form-data">
                        <input type="text" id="user_name" name="user_name" placeholder="Username" required>
                        <input type="text" id="firstmid_name" name="firstmid_name" placeholder="First & Middle Name" required>
                        <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
                        <label for="birthday" style="font-size: 14px; color: white;">Birthday:</label>
                        <input type="date" id="birthday" name="birthday" required>
                        <input type="password" id="password" name="password" placeholder="Enter Password" style="margin-top:10px;" required>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>
                        <select id="country" name="country" required>
                            <option value="">Select Country</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Singapore">Singapore</option>
                            <option value="United States">United States</option>
                        </select>
                        <div style="margin-top: 10px;">
                            <label for="id_image" style="color: white;">Upload Valid ID Image:</label>
                            <input type="file" id="id_image" name="id_image" accept="image/*" style="color:white;" required>
                        </div>
                        <div class="form-group" style="display: flex; margin-top: 5px;">
                            <input type="checkbox" id="agreeTerms" style="width: 1vw;height: 2vh; margin-right: 6px;" name="agreeTerms" required>
                            <label for="agreeTerms" style="font-size: 13px; margin-top: 3px; color: white;">I agree with the <a href="terms.html" target="_blank" style="font-size: 13px; color: blue;">terms and conditions</a></label>
                        </div>
                        <button type="submit">Register</button>
                        <button type="button" onclick="cancelForm()">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Hero section -->
         <div class="hero">
            <div class="search-bar">
                <input type="text" id="search-input" class="search-input" placeholder="SEARCH FOR SCAM/SCAMMER" style="border-radius: 20px; font-weight: 300; font-size: 18px; width: 25%; text-align: center;">
                <p>INPUT NAME, NUMBER, BANK INFO, DIGITAL WALLET INFO</p>
                <button id="search-button" class="search-button" style="border-radius: 20px; ">SEARCH</button>
            </div>
        </div> 
       
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

        <div>
            <p style="color: #fff; font-size: 22px; margin-left: 15px;">Top Categories</p>
            <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width: 97%;margin-bottom: 20px;">
        </div>
         
            <!-- Featured listings -->
            <section class="featured-listings">
                <div class="categ" data-category="home-garden">
                    <img src="images/image1.jpeg" alt="Category 1">
                    <p class="categ-label">HOME & GARDEN</p>
                </div>

                <div class="categ" data-category="entertainment">
                    <img src="images/image2.jpeg" alt="Category 2">
                    <p class="categ-label">ENTERTAINMENT</p>
                </div>

                <div class="categ" data-category="family">
                    <img src="images/image3.jpeg" alt="Category 3">
                    <p class="categ-label">FAMILY</p>
                </div>

                <div class="categ" data-category="electronics">
                    <img src="images/image4.jpeg" alt="Category 4">
                    <p class="categ-label">ELECTRONICS</p>
                </div>

                <div class="categ" data-category="hobbies">
                    <img src="images/image5.jpeg" alt="Category 5">
                    <p class="categ-label">HOBBIES</p>
                </div>

                <div class="categ" data-category="business">
                    <img src="images/image6.jpeg" alt="Category 6">
                    <p class="categ-label">BUSINESS</p>
                </div>
            </section>
        <div id="posts-container"></div>
        
    </main>
    

   

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-message"></p>
        </div>
    </div>
    
    
    
    

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
                        <li><a href="furniture.php">Furniture</a></li>
                        <li><a href="kitchenware.php">Kitchenware</a></li>
                        <li><a href="gardening.php">Gardening Tools & Supplies</a></li>
                        <li><a href="cleaning.php">Cleaning Materials</a></li>
                        <li><a href="food.php">Food</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">ENTERTAINMENT</span>
                    <ul class="subcategory-list">
                        <li><a href="musicbooks.php">Music, Books, Movies</a></li>
                        <li><a href="eventtickets.php">Event Tickets</a></li>
                        <li><a href="toys.php">Toys & Games</a></li>
                        <li><a href="pageant.php">Pageant</a></li>
                        <li><a href="sing.php">Sing/Dance Competition</a></li>
                        <li><a href="gfe.php">GFE</a></li>
                        <li><a href="massage.php">Massage</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">FAMILY</span>
                    <ul class="subcategory-list">
                        <li><a href="health.php">Health & Beauty</a></li>
                        <li><a href="baby.php">Baby & Kids</a></li>
                        <li><a href="clothes.php">Clothes</a></li>
                        <li><a href="accessories.php">Accessories</a></li>
                        <li><a href="jewelry.php">Jewelry</a></li>
                        <li><a href="pet.php">Pet Accessories</a></li>
                        <li><a href="petfood.php">Pet Food</a></li>
                        <li><a href="petpurchase.php">Pet Purchase</a></li>
                        <li><a href="weddingsupplier.php">Wedding Supplier</a></li>
                        <li><a href="weddingcoordinator.php">Wedding Coordinator</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">ELECTRONICS</span>
                    <ul class="subcategory-list">
                        <li><a href="videogames.php">Video Games</a></li>
                        <li><a href="computer.php">Personal Computer</a></li>
                        <li><a href="laptop.php">Laptop</a></li>
                        <li><a href="tablet.php">Tablet</a></li>
                        <li><a href="phone.php">Phone</a></li>
                        <li><a href="accessories.php">Accessories</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">HOBBIES</span>
                    <ul class="subcategory-list">
                        <li><a href="restaurant.php">Restaurant</a></li>
                        <li><a href="amusementpark.php">Amusement Park</a></li>
                        <li><a href="musical.php">Musical Instrument</a></li>
                        <li><a href="sports.php">Sports</a></li>
                        <li><a href="collections.php">Collections</a></li>
                        <li><a href="carsforsale.php">Cars for Sale</a></li>
                        <li><a href="parts.php">Parts</a></li>
                    </ul>
                </li>
                <li class="column">
                    <span class="category-name">BUSINESS</span>
                    <ul class="subcategory-list">
                        <li><a href="investment.php">Investment</a></li>
                        <li><a href="loan.php">Loan</a></li>
                        <li><a href="supplier.php">Supplier</a></li>
                        <li><a href="partnership.php">Partnership</a></li>
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
            <div class="column">
                <div class="logo">
                    <img src="images/Logo.png" alt="Airbnb Logo" style="width: 65%; margin-bottom: 10px;">
                </div>
            </div>
           <div class="column"></div>

                <!-- Footer Menu -->
            <div class="column" >    
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
            </div>
            
            </div>        
        </nav>
    
    
</footer>

    
    
    <!-- Link to external JavaScript file -->
    <script src="js/home.js"></script>
    <!-- Link to Flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Link to intl-tel-input JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <!-- Link to external JavaScript file -->
    <script src="js/signup6.js"></script>
    <!-- JavaScript to handle alert message -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categories = document.querySelectorAll('.categ');
            const postsContainer = document.getElementById('posts-container');

            categories.forEach(category => {
                category.addEventListener('click', () => {
                    const categoryType = category.getAttribute('data-category');
                    fetchPosts(categoryType);
                });
            });

            function fetchPosts(category) {
                // Clear previous posts
                postsContainer.innerHTML = '';

                // Fetch posts from the server
                fetch(`/get-posts.php?category=${category}`)
                    .then(response => response.json())
                    .then(posts => {
                        if (posts.length > 0) {
                            posts.forEach(post => {
                                const postElement = document.createElement('div');
                                postElement.classList.add('post');
                                postElement.innerHTML = `
                                    <h2>${post.title}</h2>
                                    <p>${post.description}</p>
                                `;
                                postsContainer.appendChild(postElement);
                            });
                        } else {
                            postsContainer.innerHTML = '<p>No posts found for this category.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching posts:', error);
                        postsContainer.innerHTML = '<p>There was an error fetching the posts. Please try again later.</p>';
                    });
            }
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categories = document.querySelectorAll('.categ');

            categories.forEach(category => {
                category.addEventListener('click', () => {
                    const categoryType = category.getAttribute('data-category');
                    redirectToCategoryPage(categoryType);
                });
            });

            function redirectToCategoryPage(category) {
                // Define the URL pattern for category pages
                const url = `/App/${category}.php`;
                
                // Redirect to the category page
                window.location.href = url;
            }
        });


    </script>
    <script>
    

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
