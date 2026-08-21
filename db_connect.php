<?php
// Database connection settings
$servername = "localhost";   // keep localhost for XAMPP
$username   = "root";        // default XAMPP user
$password   = "";            // default XAMPP password is empty
$dbname     = "shantubag_db";   // your database name
$port       = 3307;          // use 3307 since your MySQL is running on this port

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    // You can remove this line in production
    // echo "✅ Connected successfully!";
}
?>
