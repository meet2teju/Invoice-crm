<?php
$servername = "localhost";     // usually localhost
$username   = "invoi317";          // your MySQL username
$password   = "X5klp2YD00A8A";              // your MySQL password
$database   = "invoi317"; // your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


