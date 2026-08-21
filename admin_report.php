<?php
include "db_connect.php";

// Total Bookings
$booking = $conn->query("SELECT COUNT(*) AS total FROM booking") 
    or die("Booking Query Error: " . $conn->error);
$booking_count = $booking->fetch_assoc()['total'];

// Payments (Paid)
$paid = $conn->query("SELECT SUM(amount) AS total FROM booking WHERE payment_status='Paid'")
    or die("Paid Query Error: " . $conn->error);
$paid_total = $paid->fetch_assoc()['total'] ?: 0;

// Pending Payments
$pending = $conn->query("SELECT SUM(amount) AS total FROM booking WHERE payment_status='Pending'")
    or die("Pending Query Error: " . $conn->error);
$pending_total = $pending->fetch_assoc()['total'] ?: 0;

// Monthly Profit
$month = date('m');
$year  = date('Y');

// CHANGE 'date' to your actual column name!
$profit = $conn->query("
    SELECT SUM(amount) AS total 
    FROM booking 
    WHERE MONTH(date)='$month' AND YEAR(date)='$year'
") or die("Profit Query Error: " . $conn->error);

$monthly_profit = $profit->fetch_assoc()['total'] ?: 0;
?>
