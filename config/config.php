<?php
$servername = "localhost";     // usually localhost
$username   = "root";          // your MySQL username
$password   = "";              // your MySQL password
$database   = "invoi317"; // your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


