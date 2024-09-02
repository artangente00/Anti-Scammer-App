<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
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


// Retrieve the post ID from the URL parameter
$post_id = $_GET['id'];

if (!is_numeric($post_id)) {
    die("Invalid post ID.");
}

// Query the database to fetch the details of the post with the given ID
$sql = "SELECT * FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();
$post = $post_result->fetch_assoc();
$stmt->close();

if (!$post) {
    die("Post not found.");
}

// Fetch user details using the user_id from the post
$user_id_from_post = $post['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id_from_post);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

// Fetch dispute details
$sql = "SELECT dispute_text, username_dispute, dispute_date FROM post_disputes WHERE post_id = ? LIMIT 0, 25";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $post_id);
$stmt->execute();
$dispute_result = $stmt->get_result();
if (!$dispute_result) {
    die("Execute failed: " . $stmt->error);
}
$disputes = [];
while ($row = $dispute_result->fetch_assoc()) {
    $disputes[] = $row;
}
$stmt->close();

if (empty($disputes)) {
    echo "";
} else {
    // Convert PHP array to JavaScript object
    $json_disputes = json_encode($disputes);

    // Output JavaScript to log the array in the browser console
    echo "<script>";
    echo "console.log(" . $json_disputes . ");";
    echo "</script>";
}

$formatted_datepostd = date("F j, Y", strtotime($post['date_posted']));

// Fetch comment details
$sql_comment = "SELECT comment_text, username_comment, comment_date, comment_id FROM comments WHERE post_id = ?";
$stmt_comment = $conn->prepare($sql_comment);
if (!$stmt_comment) {
    die("Prepare failed: " . $conn->error);
}
$stmt_comment->bind_param("i", $post_id);
$stmt_comment->execute();
$comment_result = $stmt_comment->get_result();
if (!$comment_result) {
    die("Execute failed: " . $stmt_comment->error);
}
$comments = [];
while ($row_comment = $comment_result->fetch_assoc()) {
    $comments[] = $row_comment;
}
$stmt_comment->close();

if (empty($comments)) {
    echo "";
} else {
    // Convert PHP array to JavaScript object
    $json_comments = json_encode($comments);

    // Output JavaScript to log the array in the browser console
    echo "<script>";
    echo "console.log(" . $json_comments . ");";
    echo "</script>";
}




// Fetch the post images based on count_id
$sql = "SELECT file_name, file_path, count_id FROM post_images WHERE count_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post['count_id']);
$stmt->execute();
$image_result = $stmt->get_result();
$images = [];
while ($row = $image_result->fetch_assoc()) {
    $images[] = $row;
}
$stmt->close();

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

// Close the connection to free up resources
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
    <link rel="stylesheet" href="css/home2.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Link to Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Link to intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Link to Captcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
    /* Form Style */
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 10px;
        }

        /* Label Style */
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Input Style */
        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* File Input Style */
        input[type="file"] {
            margin-bottom: 10px;
        }

        /* Submit Button Style */
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Textarea Style */
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Styling for the form title */
        form p {
            margin: 0;
            padding: 0;
        }

        /* Styling for the form section divider */
        hr {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Styling for form section title */
        .form-section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .post-scam-heading {
            color: #fff;
            font-size: 28px;
            font-weight: 300;
            text-align: center; /* Align text at the center */
            width: 100%;
            margin: 0 auto; /* Center horizontally */
        }

                /* Basic styling for modal */
                #dispute-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        #dispute-button:hover {
            background-color:blue;
           

        }
        #dispute-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        #close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        #close-modal:hover,
        #close-modal:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .reply-button {
            margin-left: 10px;
            background-color: #d90505;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
        }
        .reply-form {
            display: none;
            margin-top: 10px;
        }
        .reply-textarea {
            width: 60vw;
            height: 100px;
            background-color: rgb(27, 23, 23);
            color: white;
            padding: 10px;
        }
        .post-reply-button {
            margin-top: 10px;
            background-color: #d90505;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
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
        <div class="row" style="width: 100vw;">  
          
            <div class="column">  
                    <div class="category-section">
                        <div class="row" style="width: 100%; margin-left:20px;"> 
                            <p style="color: white; font-weight: 100; font-size:14px; margin-left:50px;"><strong>Category:</strong><?php echo htmlspecialchars($post['category']); ?></p>
                            <p style="color: white; font-weight: 100; font-size:14px;"><strong>></strong> <?php echo htmlspecialchars($post['sub_category']); ?></p>
                        </div>
                       
                        <div class="row">
                            <hr class="navbar-divider2" style="border-radius: 40px; border: 0.5px solid #d90505; width: 15%; margin-top: 0px;">
                        </div>
                    </div>
                </div>
                <div class="column"></div>
                <div class="column"></div>
                <div class="column">
                <div class="row">
                    <button id="dispute-button" style="background-color:transparent; color:gray; font-size:18px;" onclick="openSignupPopup()">Dispute This Post</button>
                </div>
                </div>
            
        </div>

        <div class="row" style="width: 100vw;">
            <h1 class="post-title" style="color: white;"><?php echo htmlspecialchars($post['title']); ?></h1>
        </div>
        <div class="row" style="width: 100vw;">
            <div class="column" style="width: 60vw;"></div>
            <div class="column"></div>
            <div class="column"></div>
            <div class="column"></div>
            <div class="column">
                <div class="row" style="width: 30vw;">
                    <p class="post-author" style="color: white; margin-left: auto; "><?php echo htmlspecialchars($formatted_datepostd); ?></p>
                    <p class="post-author" style="color: white; ">@<?php echo htmlspecialchars($post['sc_username']); ?></p>
                </div>
            </div>
            
        </div>

        <div class="row" style="width: 100vw;">
        <?php 
            $counter = 0;
            foreach ($images as $image): 
                // Check if the count_id of the current image matches the count_id of the post
                if ($image['count_id'] != $post['count_id']) {
                    continue; // Skip this image if count_id doesn't match
                }
                
                // Display the image
                if ($counter >= 7) {
                    break; // Stop after 7 images
                }
            ?>
                <div class="column" style="flex:1; max-width: calc(100% / 7); box-sizing: border-box;">
                    <img class="post-image" style="width: 100%; height: auto; display: block;" src="<?php echo htmlspecialchars($image['file_path']); ?>" alt="<?php echo htmlspecialchars($image['file_name']); ?>">
                </div>
            <?php 
                $counter++;
            endforeach; 
            ?>
        </div>
        
        <div class="row" style="width: 100vw;">
        <p class="post-description" style="color: white; font-size: 18px; padding: 10px; width: 60vw; background-color: rgb(27, 23, 23);">
            <?php echo nl2br(htmlspecialchars($post['description'])); ?><br>
            Here's their post's link: <br> 
            <a href="<?php echo htmlspecialchars($post['fb_link']); ?>" target="_blank"><?php echo htmlspecialchars($post['fb_link']); ?></a>
        </p>

        </div>
        <?php
            if (empty($disputes)) {
                echo "";
            } else {
                foreach ($disputes as $dispute) {
                    $dispute_text = htmlspecialchars($dispute['dispute_text']);
                    $username_dispute = htmlspecialchars($dispute['username_dispute']);
                    $dispute_date = date("F j, Y", strtotime($dispute['dispute_date']));
            ?>
                    <div class="row" style="width: 100vw;">
                        <div class="dispute">
                            <p class="post-disputed" style="color: white; font-size: 16px; padding: 0px; width: 60vw; text-align: right;">
                                Disputed by @<span class="post-author" style="color: white;"><?php echo $username_dispute; ?></span> <?php echo $dispute_date; ?>
                            </p>
                            <p class="dispute-text" style="color: white; font-size: 18px; padding: 10px; width: 60vw; background-color: rgb(27, 23, 23);"><?php echo $dispute_text; ?></p>
                        </div>
                    </div>

            <?php
                }
            }
        ?>
       
        <div class="row">
            <!-- Me too button -->
            <button class="me-too-button">Me too (12)</button>
        </div>
        <div class="row">
            <p class="post-comment" style="color: white; font-size: 12px; margin-left: auto;">What is this about?</p>
        </div>
        <div class="row" style="width: 100vw;">  
                <div class="scammer-details">
                    <div class="row" style="width: 100vw;">
                        <p style="color: white; font-weight: 100; margin-right: auto; margin-left: 15px;">Scammer Details</p>
                    </div>
                    <div class="row" style="width: 100vw;">
                        <hr class="navbar-divider2" style="  margin-left: 15px; margin-top: 0px; border-radius: 40px; border: 0.5px solid #d90505; width: 8%;">
                    </div>
                     <!-- Scammer Information -->
                    <div class="row">
                        <p>Name: <?php echo htmlspecialchars($post['scammer_name']); ?></p>
                    </div>
                    <div class="row">
                        <p>CP Number: <?php echo htmlspecialchars($post['sc_phone']); ?></p>
                    </div>    
                    <div class="row">
                        <p>FB Link: <a href="https://facebook.com/toldenz" target="_blank"><?php echo htmlspecialchars($post['fb_link']); ?></a></p>
                    </div>
                    <div class="row">
                        <p>Email: <?php echo htmlspecialchars($post['sc_email']); ?></p>
                    </div>    
                    <div class="row">
                        <p>E-Wallet: <?php echo htmlspecialchars($post['sc_bankname']); ?></p>
                    </div>
                    <div class="row">
                        <p>Bank Info: <?php echo htmlspecialchars($post['sc_bankacctnumber']); ?></p>
                    </div>
                    <div class="row">
                        <p>TG Username: <?php echo htmlspecialchars($post['sc_username']); ?></p>
                    </div>
                </div>
        </div>
        <!-- Comment Section -->
        <div class="row" style="margin-left: 0px; width: 100vw; background-color:transparent;">
                <form action="comment.php" method="post" style="margin-left: 0px; width: 100vw; background-color:transparent;">
                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                    <div class="row" style="width: 100vw;">
                        <p style="color: white; font-weight: 100; margin-right: auto; margin-left: 10px;">Comments (Post a Comment)</p>
                    </div>
                    <div class="row" style="width: 100vw;">
                        <hr class="navbar-divider2" style="margin-left: 10px; margin-top: 5px; border-radius: 40px; border: 0.5px solid #d90505; width: 10%;">
                    </div>
                    <div class="row" style="width: 50vw;">
                        <textarea id="comment" name="comment_text" placeholder="Post a comment..." style="width:50vw; height: 100px; margin-top: 10px; background-color: rgb(27, 23, 23);color:white;"></textarea>
                    </div>
                    <div class="row" >
                        <button type="submit" style="margin-top: 10px; margin-right: auto; margin-left: 10px; width: 15%;">Post Comment</button>
                    </div>
                </form>
        </div>

        <div class="comments-section">
        <?php
        if (empty($comments)) {
            echo "";
        } else {
            foreach ($comments as $comment) {
                $comment_text = htmlspecialchars($comment['comment_text']);
                $username_comment = htmlspecialchars($comment['username_comment']);
                $comment_date = date("F j, Y", strtotime($comment['comment_date']));
                $comment_id = htmlspecialchars($comment['comment_id']);

                // Database configuration
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
                // Fetch replies for the current comment
                $sql_replies = "SELECT * FROM reply WHERE comment_id = ?";
                $stmt_replies = $conn->prepare($sql_replies);
                $stmt_replies->bind_param("i", $comment_id);
                $stmt_replies->execute();
                $reply_result = $stmt_replies->get_result();
                $replies = [];
                while ($reply = $reply_result->fetch_assoc()) {
                    $replies[] = $reply;
                }
        ?>
        <div class="row" style="width: 100vw;">
            <div class="comment" style="margin-right: auto; margin-left: 10px;">
                <p class="comment-text" style="color: white; font-size: 16px; margin-left: 0px; padding: 10px; width: 60vw; background-color: rgb(27, 23, 23);"><?php echo $comment_text; ?></p>
                <p class="post-comment" style="color: white; font-size: 14px; padding: 0px; width: 60vw; text-align: right;">
                    Posted by @<span class="comment-author" style="color: white;"><?php echo $username_comment; ?></span> on <?php echo $comment_date; ?>
                </p>
                <button onclick="showReplyForm('<?php echo $comment_id; ?>')" style="margin-left: 20px; background-color: transparent; color: white; border: none; cursor: pointer; padding: 5px 10px;">Reply to <?php echo $username_comment; ?></button>
                <div id="reply-form-<?php echo $comment_id; ?>" style="display: none; margin-top: 10px;">
                    <textarea id="reply-text-<?php echo $comment_id; ?>" placeholder="Write a reply..." style="width: 60vw; height: 100px; background-color: rgb(27, 23, 23); color: white; padding: 10px;"></textarea>
                    <button onclick="postReply('<?php echo $post_id; ?>', '<?php echo $comment_id; ?>', '<?php echo $user_id; ?>','<?php echo $username_comment; ?>')" style="margin-top: 10px; background-color: #d90505; color: white; border: none; cursor: pointer; padding: 5px 10px;">Post Reply</button>
                </div>
                <!-- Display replies -->
                <?php if (!empty($replies)) : ?>
                    <div class="replies">
                        <?php foreach ($replies as $reply) : ?>
                            <p class="reply-text" style="color: white; font-size: 14px; margin-left: 30px; padding: 10px; width: 60vw; background-color: rgb(27, 23, 23);"><?php echo htmlspecialchars($reply['reply_text']); ?></p>
                            <p class="post-comment" style="color: white; font-size: 12px; padding: 0px; width: 60vw; text-align: right;">
                                Posted by @<span class="comment-author" style="color: white;"><?php echo htmlspecialchars($reply['username_reply']); ?></span>
                            </p>
                            <!-- Add more styling or content for replies as needed -->
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php
        // Close the result set after using it
        $stmt_replies->close();
    }
}
?>

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

            

        

          <!-- Dispute Form Modal -->
            <div id="dispute-modal">
                <div id="dispute-modal-content">
                    <span id="close-modal">&times;</span>
                    <form id="dispute-form" method="POST" action="dispute.php">
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                        <label for="dispute_text">Explain why you dispute this post:</label>
                        <textarea id="dispute_text" name="dispute_text" required></textarea>
                        <button type="submit">Submit Dispute</button>
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

<script src="js/signup6.js"></script>
<script>
function openSignupPopup() {
    document.getElementById('signup-popup').style.display = 'block';
}

function closeSignupPopup() {
    document.getElementById('signup-popup').style.display = 'none';
}

function cancelForm() {
    closeSignupPopup();
}

function showReplyForm(commentId) {
    var form = document.getElementById('reply-form-' + commentId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function postReply(postId, commentId, userId, usernameComment) {
    var replyText = document.getElementById('reply-text-' + commentId).value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "post_reply.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert('Reply posted successfully!');
            location.reload(); // Reload the page to see the new reply
        }
    };
    xhr.send("post_id=" + postId + "&comment_id=" + commentId + "&user_id=" + userId + "&reply_text=" + encodeURIComponent(replyText));
}
</script>

<script>
        // Generate a random unique ID
    function generateUniqueId() {
        return 'id_' + Math.random().toString(36).substr(2, 9); // Generates a random alphanumeric string
    }

    // Set the unique ID in the count_id input field
    document.getElementById('count_id').value = generateUniqueId();
    </script>
<script>

    document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Log out of your account?")) {
                window.location.href = '/App/home.php';
            }
        });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
        function showAlert() {
            alert("<?php echo $alertMessage; ?>");
        }
</script>
<script>
function editPost(postId) {
    window.location.href = 'editmypost.php?id=' + <?php echo $post['post_id']; ?>;
}

</script>



</body>
</html>
