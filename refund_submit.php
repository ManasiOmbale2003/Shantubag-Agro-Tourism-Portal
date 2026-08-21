<?php
session_start();
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_name     = $_POST['user_name'];
    $booking_id    = $_POST['booking_id'];
    $payment_id    = $_POST['payment_id'];
    $amount        = $_POST['amount'];
    $payment_mode  = $_POST['payment_mode'];
    $reason        = $_POST['reason'];
    $request_date  = date("Y-m-d");

    // Insert refund request
    $sql = "INSERT INTO refunds 
            (user_name, booking_id, payment_id, amount, payment_mode, reason, status, request_date)
            VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL ERROR: " . $conn->error);
    }

    $stmt->bind_param("siidsss", 
        $user_name,
        $booking_id,
        $payment_id,
        $amount,
        $payment_mode,
        $reason,
        $request_date
    );

    if ($stmt->execute()) {
        echo "<script>alert('Refund Request Submitted!'); window.location='refund_history.php';</script>";
    } else {
        echo "SQL ERROR: " . $stmt->error;
    }
}
?>
