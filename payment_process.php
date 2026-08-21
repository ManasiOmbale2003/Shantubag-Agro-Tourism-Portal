<?php
session_start();
include("db_connect.php");

// ✅ Ensure required fields are provided
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id   = $_POST['booking_id'] ?? null;
    $amount       = $_POST['amount'] ?? 0;
    $payment_mode = $_POST['payment_mode'] ?? 'Unknown';
    $status       = 'Paid'; // Default success
    $receipt_path = null;

    // ✅ Handle file upload if provided
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = "uploads/receipts/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $file_name = time() . "_" . basename($_FILES['receipt']['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['receipt']['tmp_name'], $target_path)) {
            $receipt_path = $target_path;
        }
    }

    // ✅ Begin database transaction
    $conn->begin_transaction();

    try {
        // 1️⃣ Insert the payment (without transaction_id first)
        $stmt = $conn->prepare("
            INSERT INTO payments (booking_id, amount, payment_mode, status, receipt, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("idsss", $booking_id, $amount, $payment_mode, $status, $receipt_path);
        $stmt->execute();

        // 2️⃣ Get the auto-increment ID and generate a transaction ID
        $payment_id = $conn->insert_id;
        $transaction_id = 'TXN' . str_pad($payment_id, 6, '0', STR_PAD_LEFT);

        // 3️⃣ Update the payment record with transaction ID
        $update = $conn->prepare("UPDATE payments SET transaction_id = ? WHERE id = ?");
        $update->bind_param("si", $transaction_id, $payment_id);
        $update->execute();

        // 4️⃣ Update booking status (optional)
        if (!empty($booking_id)) {
            $update_booking = $conn->prepare("UPDATE booking SET payment_status = 'Paid' WHERE id = ?");
            $update_booking->bind_param("i", $booking_id);
            $update_booking->execute();
        }

        // 5️⃣ Add a notification for the admin dashboard
        $msg = "✅ Payment received for Booking ID: $booking_id (Transaction ID: $transaction_id)";
        $notif = $conn->prepare("INSERT INTO notifications (message, type, status, created_at) VALUES (?, 'payment', 'unread', NOW())");
        $notif->bind_param("s", $msg);
        $notif->execute();

        // ✅ Commit the transaction
        $conn->commit();

        // 6️⃣ Display a success message to the user
        echo "
        <div style='
            font-family: Arial;
            background: #d4edda;
            color: #155724;
            padding: 20px;
            margin: 50px auto;
            border-radius: 10px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        '>
            <h2>✅ Payment Successful!</h2>
            <p><strong>Transaction ID:</strong> $transaction_id</p>
            <p><strong>Amount:</strong> ₹" . number_format($amount, 2) . "</p>
            <p><strong>Payment Mode:</strong> $payment_mode</p>
            <p>Thank you for your payment. Your transaction has been recorded.</p>
            <a href='user_dashboard.php' style='text-decoration:none; color:white; background:#28a745; padding:10px 20px; border-radius:5px;'>Back to Dashboard</a>
        </div>
        ";

    } catch (Exception $e) {
        // ❌ Rollback in case of any error
        $conn->rollback();
        echo "
        <div style='
            font-family: Arial;
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            margin: 50px auto;
            border-radius: 10px;
            max-width: 500px;
            text-align: center;
        '>
            <h2>❌ Payment Failed!</h2>
            <p>Error: " . htmlspecialchars($e->getMessage()) . "</p>
            <a href='booking.php' style='text-decoration:none; color:white; background:#dc3545; padding:10px 20px; border-radius:5px;'>Try Again</a>
        </div>
        ";
    }
} else {
    echo "<h3 style='text-align:center; color:red;'>Invalid Request</h3>";
}
?>
