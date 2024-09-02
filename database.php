<?php
$hostname = '195.26.253.211'; // Change this to your database hostname
$username = 'admin'; // Change this to your database username
$password = 'admin'; // Change this to your database password
$database = 'test'; // Change this to your database name

// Create a connection to MySQL database
$connection = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    // Connection failed
    die("Connection failed: " . mysqli_connect_error());
} else {
    // Connection successful
    echo "Connected successfully!";
}

// Close the database connection (optional, as PHP will automatically close it at the end of script execution)
mysqli_close($connection);
?>
