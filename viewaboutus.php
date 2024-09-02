<?php

// Database configuration
//$hostname = '195.26.253.211'; // Change this to your database hostname
//$username = 'admin'; // Change this to your database username
//$password = 'admin'; // Change this to your database password
//$database = 'test'; // Change this to your database name

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Fetch the content
$result = $conn->query("SELECT content, status, description FROM editpage WHERE id = 1");
$row = $result->fetch_assoc();
$content = $row['content'];
$status = $row['status'];
$description = $row['description'];

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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="css/home2.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Link to Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Link to intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <!-- Link to Captcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

    <!-- Main content section -->
    <main>
      <!-- About Us Section -->
    <div class="row" style="padding: 10px; width: 90%; margin-left: 20px;margin-right: 20px;"> 
        <section class="about-us" style="color: #fff; ">
            <div class="row" style="padding: 10px; width: 90%"> 
                <h2>About Us</h2>
            </div>    
            <div class="row" style="padding: 10px; width: 90%">
                <div>
                    <?php echo $content_1; ?>
                </div>
            </div>
        </section>
    </div>
    
    </main>

    <div id="signup-popup" style="display: none;">
        <div class="popup-content">
            <button type="button" onclick="closeSignupPopup()" style="position: absolute; top: 10px; right: 10px; font-size: 18px; color: white; background-color: green; border: none; cursor: pointer;">&times;</button>
            <div class="row" style="display: flex; justify-content: space-evenly; width: 100%; margin-top: 40px; margin-bottom: 20px;">
                <div class="column" style="width: 100%;">
                    <div class="row" style="display: flex; justify-content: space-evenly; width: 100%;">
                        <h2 style="color: white;">SIGN IN</h2>
                    </div>
                    <div class="row" style="display: flex; border-right: 1px solid red; padding-right: 30px; justify-content: space-evenly; width: 100%; margin-top: 10px;">
                        <form id="signin-form" action="signin.php" method="post" >
                            <!-- Sign In Form Fields -->
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required><br><br>
                            <label for="password">Password:</label>
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
                        <form id="signup-form" action="signup.php" method="post">
                            <input type="text" id="user_name" name="user_name" placeholder="Username">
                            <input type="text" id="firstmid_name" name="firstmid_name" placeholder="First & Middle Name" required>
                            <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
                            <label for="birthday" style="font-size: 14px;">Birthday:</label>
                            <input type="date" id="birthday" name="birthday" placeholder="Birthday" style="width: 13vw; height: 5vh; margin-bottom: 6px; border-radius: 5px;" required>
                            <input type="password" id="password" name="password" placeholder="Enter Password" required>
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
                                <label for="id_image">Upload Valid ID Image:</label>
                                <input type="file" id="id_image" name="id_image" accept="image/*" style="color: white;" required>
                            </div>
                            <div class="form-group" style="display: flex; margin-top: 5px;">
                                <input type="checkbox" id="agreeTerms" style="width: 1vw;height: 2vh; margin-right: 6px;" name="agreeTerms" required>
                                <label for="agreeTerms" style="font-size: 13px; margin-top: 3px;">I agree with the <a href="terms.html" target="_blank" style="font-size: 13px; color: blue;">terms and conditions</a></label>
                            </div>
                            <button type="submit" >Register</button>
                            <button type="button" onclick="cancelForm()">Cancel</button>
                        </form>
                        
                    </div>
                </div>
            </div>
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
<script src="js/signup6.js"></script>
<!-- Link to external JavaScript file -->
<script src="js/home.js"></script>
<script>
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        const formData = new FormData();
                        formData.append('upload', file);

                        fetch('http://localhost/App/admin/upload_image.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.url) {
                                resolve({
                                    default: result.url
                                });
                            } else {
                                reject(result.error && result.error.message ? result.error.message : 'Upload failed');
                            }
                        })
                        .catch(error => {
                            reject(`Upload failed with error: ${error}`);
                        });
                    }));
            }

            abort() {
                // This method should abort the upload process
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        ClassicEditor
            .create(document.querySelector('#content'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        '|',
                        'imageStyle:full',
                        'imageStyle:side',
                        '|',
                        'imageStyle:alignLeft',
                        'imageStyle:alignCenter',
                        'imageStyle:alignRight'
                    ],
                    styles: [
                        'full',
                        'side',
                        'alignLeft',
                        'alignCenter',
                        'alignRight'
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>
