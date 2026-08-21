<?php
include("db_connect.php");

if (!isset($_GET['id'])) die("❌ Invalid Request");
$id = $_GET['id'];

$sql = "DELETE FROM bookings WHERE booking_id=$id";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('🗑 Booking Deleted');window.location='manage_bookings.php';</script>";
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
