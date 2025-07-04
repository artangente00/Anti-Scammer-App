<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $content = $_POST['content'];
    $description = $_POST['description'];

    // Save the content to the database
    $stmt = $conn->prepare("UPDATE editpage SET content = ?, status = ?, description = ? WHERE id = 1");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('sss', $content, $status, $description);
    $stmt->execute();
    if ($stmt->error) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // Redirect to viewaboutus.php
    header("Location: viewaboutus.php");
    exit();
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
    <title>Edit About Us</title>
    <link rel="stylesheet" href="css/home2.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Link to Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Link to intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <!-- Link to Captcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
 

    
    <style>
        /* Center align */
        .image-style-align-center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        /* Left align */
        .image-style-align-left {
            display: block;
            float: left;
            margin-right: 1em;
            margin-bottom: 1em;
        }

        /* Right align */
        .image-style-align-right {
            display: block;
            float: right;
            margin-left: 1em;
            margin-bottom: 1em;
        }

        /* Full width (default) */
        .image-style-full {
            display: block;
            width: 100%;
            margin-bottom: 1em;
        }

        /* Side image styles */
        .image-style-side {
            float: left;
            margin-right: 1em;
            margin-bottom: 1em;
            max-width: 50%;
        }

        /* Optional: Clear floats to ensure text wraps correctly */
        figure {
            overflow: hidden;
        }

        /* Image resizing */
        .ck-content img {
            max-width: 100%;  /* Ensure image doesn't overflow the container */
            height: auto;    /* Maintain aspect ratio */
            display: block;  /* Ensure image behaves like a block element */
        }

        /* Specific sizes for resizing */
        .ck-content img[style*="width:25%"] {
            width: 25% !important;
        }

        .ck-content img[style*="width:50%"] {
            width: 50% !important;
        }

        .ck-content img[style*="width:75%"] {
            width: 75% !important;
        }
       .sticky-header {
        position: -webkit-sticky; /* For Safari */
        position: sticky;
        top: 0;
        width: 100%;
        z-index: 1000; /* Ensure the header stays above other content */
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Optional: Adds a subtle shadow */
        background-color: #201c34;
        color: white;
        
    }

    /* Header Menu styling */
    .header-menu {
        display: flex;
        justify-content: flex-start; /* Align menu items to the left */
        align-items: center;
        margin-top: 10px;
        margin-left: 20px;
        padding: 0;
        list-style: none;
        font-size: 18px;
        color: white;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px; /* Adjust padding as needed */
    }
    .button-cancel:hover {
            background-color: #007bff;
        }
    </style>

</head>
<body>
<header class="sticky-header">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo" style="width: 30%;">
             <a href="https://sagishi.com/admin/homeadmin.php">
                    <img src="images/Logo.png" alt="Airbnb Logo" style="width: 30%; margin-top:10px;">
            </a>
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
    
</header>

        
        <!-- Button "Post a Scam" -->
        <div class="post-scam-button">
            <a href="postscam.php" class="post-scam-btn">POST A SCAM</a>
        </div>

    <!-- Main content section -->
    <main>
    
    <div class="row" style="justify-content: left; width: 100%">
            <p style="color: #fff; font-size: 32px; margin-left: 10px; margin-bottom: 0px; font-wight:500;">Title: About Us</p>
        </div>
        <div class="row" style="justify-content: left; width: 100%">
            <hr class="navbar-divider2" style="border-radius: 40px; border: 1px solid #d90505; width:97% ;margin-bottom: 20px;">
        </div>
        <form method="POST" action="editaboutus.php" enctype="multipart/form-data">
            <div class="row" style="justify-content: left; width: 100%">
                <!-- Replace textarea with CKEditor instance for description -->
                <textarea id="description" rows="4" name="description" style="width: 50%; margin-left: 10px; border-radius:10px;"><?php echo htmlspecialchars($description_1); ?></textarea>
            </div>
            <div class="row" style="justify-content: left; width: 15%; padding-top:10px; display: block; padding-bottom:10px; margin-left: 10px; margin-top: 10px; background-color:white;">
                <div class="row" style="width:100%;">
                    <label style="color: black; font-size: 16px; margin-right: auto; margin-left: 10px; display: block;">Status:</label>
                </div>
                <div class="row" style="width:100%; display: flex; margin-top: 10px;">
                    <label style="color: black; font-size: 16px; margin-right: auto; display: block;">
                        <input type="radio" name="status" value="disabled" <?php echo ($status_1 === 'disabled') ? 'checked' : ''; ?>> Disabled
                    </label>
                    <label style="color: black; font-size: 16px; display: block; margin-right: auto;">
                        <input type="radio" name="status" value="enabled" <?php echo ($status_1 === 'enabled') ? 'checked' : ''; ?>> Enabled
                    </label>
                </div>
            </div>

            <div class="row" style="justify-content: left; width: 100%; margin-top: 20px;">
                <!-- Replace textarea with CKEditor instance for content -->
                <textarea id="content" name="content" style="width: 97%; height:50vh;  margin-left: 10px;"><?php echo htmlspecialchars($content_1); ?></textarea>
            </div>

            <div class="row" style="width:100%; margin-top: 20px; display: flex; justify-content: left; margin-left: 10px;">
                <button type="submit" class="button button-save" style="width:15%;">Save Changes</button>
                <button type="button" onclick="window.location.href='viewaboutus.php'" class="button button-cancel">Cancel</button>
            </div>
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
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo', '|',
                        'alignment:left', 'alignment:center', 'alignment:right', '|',
                        'imageUpload', 'imageResize', '|',
                        'fontColor', 'fontBackgroundColor'  // Add color options to the toolbar
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', '|',
                        'imageStyle:full', 'imageStyle:side', '|',
                        'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight', '|',
                        'resizeImage'
                    ],
                    styles: [
                        'full', 'side', 'alignLeft', 'alignCenter', 'alignRight'
                    ],
                    resizeOptions: [
                        {
                            name: 'resizeImage:original',
                            value: null,
                            label: 'Original size'
                        },
                        {
                            name: 'resizeImage:25',
                            value: '25',
                            label: '25%'
                        },
                        {
                            name: 'resizeImage:50',
                            value: '50',
                            label: '50%'
                        },
                        {
                            name: 'resizeImage:75',
                            value: '75',
                            label: '75%'
                        }
                    ],
                    resizeUnit: '%'
                },
                fontColor: {
                    colors: [
                        {
                            color: 'hsl(0, 0%, 0%)',
                            label: 'Black'
                        },
                        {
                            color: 'hsl(0, 0%, 30%)',
                            label: 'Dim grey'
                        },
                        {
                            color: 'hsl(0, 0%, 60%)',
                            label: 'Grey'
                        },
                        {
                            color: 'hsl(0, 0%, 90%)',
                            label: 'Light grey'
                        },
                        {
                            color: 'hsl(0, 0%, 100%)',
                            label: 'White',
                            hasBorder: true
                        },
                        // Add more colors as needed
                    ]
                },
                fontBackgroundColor: {
                    colors: [
                        {
                            color: 'hsl(0, 75%, 60%)',
                            label: 'Red'
                        },
                        {
                            color: 'hsl(30, 75%, 60%)',
                            label: 'Orange'
                        },
                        {
                            color: 'hsl(60, 75%, 60%)',
                            label: 'Yellow'
                        },
                        {
                            color: 'hsl(90, 75%, 60%)',
                            label: 'Light green'
                        },
                        {
                            color: 'hsl(120, 75%, 60%)',
                            label: 'Green'
                        },
                        {
                            color: 'hsl(150, 75%, 60%)',
                            label: 'Aquamarine'
                        },
                        {
                            color: 'hsl(180, 75%, 60%)',
                            label: 'Turquoise'
                        },
                        {
                            color: 'hsl(210, 75%, 60%)',
                            label: 'Light blue'
                        },
                        {
                            color: 'hsl(240, 75%, 60%)',
                            label: 'Blue'
                        },
                        {
                            color: 'hsl(270, 75%, 60%)',
                            label: 'Purple'
                        },
                        // Add more colors as needed
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>


<script>
    document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Log out of your account?")) {
                window.location.href = '/App/home.php';
            }
        });
</script>


</body>
</html>
