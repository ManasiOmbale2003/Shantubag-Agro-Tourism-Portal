<?php
session_start();
include("db_connect.php");

// ✅ 1. Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location:cancel_Booking_form.php");
    exit();
}

$id = intval($_SESSION['id']);

// ✅ 2. Handle booking cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['reason'])) {
    $booking_id = intval($_POST['booking_id']);
    $reason = trim($_POST['reason']);

    if ($reason !== '') {
        $stmt = $conn->prepare("
            UPDATE booking 
            SET status='Cancelled', 
                cancel_reason=?, 
                payment_status='Refund Requested'
            WHERE booking_id=? 
              AND id=? 
              AND status NOT IN ('Cancelled', 'Refunded')
        ");
        $stmt->bind_param("sii", $reason, $booking_id, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Booking cancelled and refund request sent!'); window.location='user_bookings.php';</script>";
            exit();
        } else {
            echo "<p style='color:red;'>❌ Error cancelling booking: " . htmlspecialchars($stmt->error) . "</p>";
        }
        $stmt->close();
    }
}

// ✅ 3. Fetch all user bookings
$sql = "
    SELECT booking_id, booking_type, room_type, package_type, checkin_date, checkout_date, total_price,
           status, payment_status, cancel_reason, created_at
    FROM booking 
    WHERE id = ?
    ORDER BY created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #fbc2eb, #a6c1ee);
        padding: 30px;
        margin: 0;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
        font-size: 14px;
    }
    th {
        background: #0073e6;
        color: white;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    textarea {
        width: 95%;
        height: 50px;
        resize: none;
        border-radius: 6px;
        padding: 6px;
        font-size: 13px;
    }
    .cancel-btn {
        background: #ff4d4d;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
    }
    .cancel-btn:hover {
        background: #cc0000;
    }
    .status {
        font-weight: bold;
    }
    .status.Confirmed { color: green; }
    .status.Cancelled { color: #ff4d4d; }
    .status.Refunded { color: #0073e6; }
    .status.Refund\ Requested { color: orange; }
    .no-data {
        text-align: center;
        color: gray;
        margin-top: 20px;
        font-size: 16px;
    }
</style>
</head>
<body>

<h2>My Bookings</h2>

<?php if ($result->num_rows > 0) { ?>
<table>
    <tr>
        <th>ID</th>
        <th>Booking Type</th>
        <th>Room/Package</th>
        <th>Check-In</th>
        <th>Check-Out</th>
        <th>Total (₹)</th>
        <th>Status</th>
        <th>Payment Status</th>
        <th>Cancel Reason</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($row['booking_id']); ?></td>
        <td><?= htmlspecialchars($row['booking_type']); ?></td>
        <td><?= htmlspecialchars($row['room_type'] ?: $row['package_type']); ?></td>
        <td><?= htmlspecialchars($row['checkin_date']); ?></td>
        <td><?= htmlspecialchars($row['checkout_date']); ?></td>
        <td><?= htmlspecialchars($row['total_price']); ?></td>
        <td class="status <?= htmlspecialchars(str_replace(' ', '\\ ', $row['status'])); ?>">
            <?= htmlspecialchars($row['status']); ?>
        </td>
        <td><?= htmlspecialchars($row['payment_status'] ?: 'Pending'); ?></td>
        <td><?= htmlspecialchars($row['cancel_reason'] ?: '-'); ?></td>
        <td>
            <?php if ($row['status'] === 'Confirmed') { ?>
                <form method="POST" onsubmit="return confirmCancel(this);">
                    <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
                    <textarea name="reason" placeholder="Reason..." required></textarea><br>
                    <button type="submit" class="cancel-btn">Cancel</button>
                </form>
            <?php } else { echo '-'; } ?>
        </td>
    </tr>
    <?php } ?>
</table>
<?php } else { ?>
    <p class="no-data">No bookings found.</p>
<?php } ?>

<script>
function confirmCancel(form) {
    return confirm("Are you sure you want to cancel this booking?");
}
</script>

</body>
</html>

<?php 
$stmt->close();
$conn->close();
?>
