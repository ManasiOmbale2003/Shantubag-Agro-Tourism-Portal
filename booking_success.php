<?php
include "db_connect.php";

$txn = $_GET['txn'] ?? '';
if (!$txn) {
    echo "<h3 style='text-align:center;color:red;'>Invalid Access!</h3>";
    exit;
}

// Fetch booking and payment details
$stmt = $conn->prepare("
    SELECT b.name, b.email, b.booking_type, b.checkin, b.checkout, b.guests,
           p.amount, p.payment_mode, p.transaction_id, p.created_at
    FROM payments p
    JOIN booking b ON p.booking_id = b.id
    WHERE p.transaction_id = ?
");
$stmt->bind_param("s", $txn);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<h3 style='text-align:center;color:red;'>Transaction not found!</h3>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking Success - Shantubag Agro Portal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body {
    background: #f5f7fa;
    font-family: 'Poppins', sans-serif;
}
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
</head>
<body>
<div class="container mt-5">
  <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
    <h3 class="text-success text-center mb-3">🎉 Payment Successful!</h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
    <p><strong>Booking Type:</strong> <?= htmlspecialchars($data['booking_type']) ?></p>
    <p><strong>Check-in:</strong> <?= htmlspecialchars($data['checkin']) ?></p>
    <p><strong>Check-out:</strong> <?= htmlspecialchars($data['checkout']) ?></p>
    <p><strong>Guests:</strong> <?= htmlspecialchars($data['guests']) ?></p>
    <hr>
    <p><strong>Transaction ID:</strong> <?= htmlspecialchars($data['transaction_id']) ?></p>
    <p><strong>Payment Mode:</strong> <?= htmlspecialchars($data['payment_mode']) ?></p>
    <p><strong>Amount Paid:</strong> ₹<?= number_format($data['amount'], 2) ?></p>
    <p class="text-muted"><small>Date: <?= htmlspecialchars($data['created_at']) ?></small></p>
    <div class="text-center mt-3">
      <a href="user_Dashboard.php" class="btn btn-success">Go to Dashboard</a>
    </div>
  </div>
</div>
</body>
</html>
