<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
include "db_connect.php";

$user_id = $_SESSION['user_id'];

// Handle cancellation request
if(isset($_GET['cancel'])){
    $booking_id = intval($_GET['cancel']);
    $stmt = $conn->prepare("UPDATE bookings SET status='Cancelled' WHERE booking_id=? AND user_id=? AND status='Pending'");
    $stmt->bind_param("ii",$booking_id,$user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: user_bookings.php");
    exit;
}

// Fetch user bookings
$res = $conn->query("SELECT b.*, r.name AS room_name, p.name AS package_name
FROM bookings b
LEFT JOIN rooms r ON b.room_id=r.room_id
LEFT JOIN packages p ON b.package_id=p.package_id
WHERE b.user_id=$user_id ORDER BY b.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>My Bookings</title>
<style>
table {width:100%; border-collapse:collapse; margin-top:20px;}
th, td {border:1px solid #ddd; padding:8px; text-align:center;}
th {background:#1976d2; color:white;}
button {padding:6px 10px; border:none; border-radius:5px; cursor:pointer;}
.cancel {background:#dc3545; color:white;}
.status-pending {color:orange; font-weight:bold;}
.status-confirmed {color:green; font-weight:bold;}
.status-cancelled {color:red; font-weight:bold;}
</style>
</head>
<body>
<h2>My Bookings</h2>
<table>
<tr>
    <th>ID</th>
    <th>Type</th>
    <th>Room / Package</th>
    <th>Check-in</th>
    <th>Check-out</th>
    <th>Guests</th>
    <th>Total Price</th>
    <th>Status</th>
    <th>Action</th>
</tr>
<?php while($row=$res->fetch_assoc()): ?>
<tr>
    <td><?= $row['booking_id'] ?></td>
    <td><?= $row['booking_type'] ?></td>
    <td><?= $row['booking_type']=='Room' ? $row['room_name'] : $row['package_name'] ?></td>
    <td><?= $row['checkin'] ?></td>
    <td><?= $row['checkout'] ?></td>
    <td><?= $row['guests'] ?></td>
    <td>₹<?= number_format($row['total_price'],2) ?></td>
    <td class="status-<?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
    <td>
        <?php if($row['status']=='Pending'): ?>
            <a href="?cancel=<?= $row['booking_id'] ?>"><button class="cancel">Cancel</button></a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
