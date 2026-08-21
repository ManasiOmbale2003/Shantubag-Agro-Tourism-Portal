<?php
session_start();
include("db_connect.php");

// User must be logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['fullname'];

// Fetch refund history for logged-in user
$sql = "SELECT * FROM refunds WHERE user_name = ? ORDER BY refund_id DESC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL ERROR: " . $conn->error);
}

$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Refund History</title>

    <style>
        body {font-family: Poppins, Arial;background:#eef6f3;margin:0;padding:20px;}
        .container {max-width:900px;margin:auto;background:#fff;padding:20px;border-radius:10px;
            box-shadow:0 2px 12px rgba(0,0,0,0.1);}
        h2 {text-align:center;margin-bottom:20px;}

        table {width:100%;border-collapse:collapse;margin-top:20px;}
        th,td {padding:10px;border-bottom:1px solid #ccc;text-align:center;}

        th {background:#28a745;color:#fff;}
        tr:hover {background:#f5f9f6;}

        .pending {color:#d68100;font-weight:bold;}
        .approved {color:#2e8b57;font-weight:bold;}
        .rejected {color:#d63031;font-weight:bold;}
    </style>
</head>
<body>

<div class="container">
    <h2>My Refund History</h2>

    <table>
        <tr>
            <th>Refund ID</th>
            <th>Booking ID</th>
            <th>Payment ID</th>
            <th>Amount (₹)</th>
            <th>Mode</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Request Date</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['refund_id'] ?></td>
            <td><?= $row['booking_id'] ?></td>
            <td><?= $row['payment_id'] ?></td>
            <td><?= $row['amount'] ?></td>
            <td><?= $row['payment_mode'] ?></td>
            <td><?= $row['reason'] ?></td>

            <td>
                <?php if ($row['status'] == "Pending") { ?>
                    <span class="pending">Pending</span>
                <?php } elseif ($row['status'] == "Approved") { ?>
                    <span class="approved">Approved</span>
                <?php } else { ?>
                    <span class="rejected">Rejected</span>
                <?php } ?>
            </td>

            <td><?= $row['request_date'] ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>
