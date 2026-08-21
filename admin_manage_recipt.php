<?php
include "db_connect.php";

// Handle Approve/Reject
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE bookings SET receipt_status='Approved' WHERE id=$id");
    header("Location: admin_manage_receipts.php"); exit();
}
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $conn->query("UPDATE bookings SET receipt_status='Rejected' WHERE id=$id");
    header("Location: admin_manage_receipts.php"); exit();
}

// Fetch bookings
$result = $conn->query("SELECT * FROM bookings ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Receipts</title>
    <style>
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th,td { border:1px solid #ddd; padding:10px; text-align:center; }
        th { background:#6c63ff; color:white; }
        img { max-width:80px; border-radius:6px; }
        .btn { padding:6px 12px; border-radius:5px; text-decoration:none; color:white; }
        .approve { background:#2AA876; }
        .approve:hover { background:#1a7d5a; }
        .reject { background:#e63946; }
        .reject:hover { background:#b71c1c; }
        .status-pending { color:#ff9800; font-weight:bold; }
        .status-approved { color:#2AA876; font-weight:bold; }
        .status-rejected { color:#e63946; font-weight:bold; }
    </style>
</head>
<body>
<h2>📑 Manage Booking Receipts</h2>
<table>
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Booking Type</th>
        <th>Payment Mode</th><th>Receipt</th><th>Status</th><th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['booking_type'] ?></td>
        <td><?= $row['payment_mode'] ?></td>
        <td>
            <?php if($row['receipt']) { ?>
                <a href="<?= $row['receipt'] ?>" target="_blank">
                    <img src="<?= $row['receipt'] ?>" alt="Receipt">
                </a>
            <?php } else { echo "No Receipt"; } ?>
        </td>
        <td class="status-<?= strtolower($row['receipt_status']) ?>">
            <?= $row['receipt_status'] ?>
        </td>
        <td>
            <?php if($row['receipt_status']=="Pending") { ?>
                <a class="btn approve" href="?approve=<?= $row['id'] ?>" onclick="return confirm('Approve this receipt?')">Approve</a>
                <a class="btn reject" href="?reject=<?= $row['id'] ?>" onclick="return confirm('Reject this receipt?')">Reject</a>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
