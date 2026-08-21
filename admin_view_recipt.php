<?php
include "../db_connect.php";

$id = $_GET['id'] ?? 0;

// Fetch booking details
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("❌ Receipt not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Receipt - <?= htmlspecialchars($booking['id']); ?></title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #e9f5ea;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.receipt-box {
    background: #fff;
    padding: 30px 40px;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    width: 420px;
    text-align: center;
}
h2 { color: #2e462d; margin-bottom: 10px; }
p { margin: 6px 0; font-size: 14px; }
.status {
    font-weight: bold;
    color: <?= ($booking['payment_status'] == 'Paid') ? 'green' : 'red'; ?>;
}
.btn {
    display:inline-block;
    margin-top:20px;
    padding:10px 20px;
    background:#2AA876;
    color:white;
    text-decoration:none;
    border-radius:8px;
}
</style>
</head>
<body>
<div class="receipt-box">
    <h2>🎉 Booking & Payment Receipt</h2>
    <p><b>Booking ID:</b> <?= $booking['id']; ?></p>
    <p><b>Name:</b> <?= htmlspecialchars($booking['name']); ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($booking['email']); ?></p>
    <p><b>Booking Type:</b> <?= htmlspecialchars($booking['booking_type']); ?></p>
    <p><b>Guests:</b> <?= $booking['guests']; ?></p>
    <p><b>Check-in:</b> <?= $booking['checkin']; ?></p>
    <p><b>Check-out:</b> <?= $booking['checkout']; ?></p>
    <p><b>Total Price:</b> ₹<?= number_format($booking['total_price'], 2); ?></p>
    <p><b>Status:</b> <span class="status"><?= htmlspecialchars($booking['payment_status']); ?></span></p>
    <a href="manage_bookings.php" class="btn">← Back</a>
</div>
</body>
</html>
