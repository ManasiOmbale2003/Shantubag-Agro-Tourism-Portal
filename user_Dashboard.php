<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include "db_connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard - Shantubag Agro Tourism</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #e8f4f8;
    color: #333;
}
header {
    background: #1976d2;
    color: white;
    padding: 15px;
    text-align: center;
    font-size: 1.5em;
    font-weight: bold;
}
.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 30px;
    gap: 20px;
}
.card {
    background: white;
    width: 220px;
    height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
    text-decoration: none;
    color: #333;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.card h3 { margin:0 0 10px 0; color: #1976d2; }
.card p { font-size: 14px; line-height: 1.4; color: #555; }
</style>
</head>
<body>

<header>
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?> - User Dashboard
</header>

<div class="container">
    <a href="facilities.php" class="card">
        <h3>🏠 Facilities</h3>
        <p>See available facilities</p>
    </a>

    <a href="gallery.php" class="card">
        <h3>🖼 Gallery</h3>
        <p>Explore photos</p>
    </a>

    <a href="packages.php" class="card">
        <h3>📦 View Packages</h3>
        <p>See available farm packages</p>
    </a>

    <a href="user_bookings.php" class="card">
        <h3>📑 My Bookings</h3>
        <p>Check your booked packages</p>
    </a>

    <a href="payment_history.php" class="card">
        <h3>💳 Payment History</h3>
        <p>View past payments</p>
    </a>

    <a href="feedback.php" class="card">
        <h3>⭐ Feedback</h3>
        <p>Give your feedback</p>
    </a>

    <a href="contact.php" class="card">
        <h3>📞 Contact Us</h3>
        <p>Get in touch</p>
    </a>

    <a href="logout.php" class="card">
        <h3>🚪 Logout</h3>
        <p>Sign out</p>
    </a>
</div>

</body>
</html>
