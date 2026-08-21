<?php
include "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $booking_type = $_POST['booking_type'];
    $room_type = $_POST['room_type'] ?? '';
    $package_type = $_POST['package_type'] ?? '';
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $guests = $_POST['guests'];
    $payment_mode = $_POST['payment_mode'];
    $created_at = date('Y-m-d H:i:s');

    // ✅ Handle receipt upload
    $receipt_path = '';
    if (isset($_FILES['receipt']) && $_FILES['receipt']['name']) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $receipt_path = $target_dir . time() . '_' . basename($_FILES['receipt']['name']);
        move_uploaded_file($_FILES['receipt']['tmp_name'], $receipt_path);
    }

    // ✅ Calculate total price (example logic)
    $total_price = 0;
    if ($booking_type === 'Room') {
        if ($room_type === 'Cottage') $total_price = 2000 * $guests;
        elseif ($room_type === 'Tent') $total_price = 1500 * $guests;
        elseif ($room_type === 'Dormitory') $total_price = 1000 * $guests;
    } elseif ($booking_type === 'Package') {
        if ($package_type === 'Farm Visit') $total_price = 1200 * $guests;
        elseif ($package_type === 'Weekend Stay') $total_price = 4500 * $guests;
        elseif ($package_type === 'Adventure Package') $total_price = 2800 * $guests;
    }

    // Default values
    $payment_status = 'Pending';
    $cancel_reason = '';
    $status = 'Active';

    // ✅ Insert into database
    $sql = "INSERT INTO bookings 
    (name, email, phone, booking_type, room_type, package_type, checkin, checkout, guests, total_price, payment_mode, receipt, created_at, payment_status, cancel_reason, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('❌ SQL Prepare failed: ' . $conn->error);
    }

    // ✅ Bind parameters (16 total)
    $stmt->bind_param(
        "ssssssssissssss",
        $name,
        $email,
        $phone,
        $booking_type,
        $room_type,
        $package_type,
        $checkin,
        $checkout,
        $guests,
        $total_price,
        $payment_mode,
        $receipt_path,
        $created_at,
        $payment_status,
        $cancel_reason,
        $status
    );

    // ✅ Execute and redirect
    if ($stmt->execute()) {
        // success
        header("Location: booking_success.php");
        exit();
    } else {
        echo "<script>alert('❌ Booking failed. Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
