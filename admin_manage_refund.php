<?php
session_start();
include("../db_connect.php");

// OPTIONAL: Check if admin is logged in
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: admin_login.php");
//     exit;
// }

// APPROVE Refund
if (isset($_GET['approve'])) {
    $refund_id = $_GET['approve'];

    $sql = "UPDATE refunds SET status='Approved' WHERE refund_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $refund_id);
    $stmt->execute();

    echo "<script>alert('Refund Approved Successfully'); window.location='AdminDashboard.php';</script>";
    exit;
}

// REJECT Refund
if (isset($_GET['reject'])) {
    $refund_id = $_GET['reject'];

    $sql = "UPDATE refunds SET status='Rejected' WHERE refund_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $refund_id);
    $stmt->execute();

    echo "<script>alert('Refund Rejected Successfully'); window.location='admin_manage_refunds.php';</script>";
    exit;
}

// FETCH ALL REFUNDS
$refunds = $conn->query("SELECT * FROM refunds ORDER BY refund_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Refunds</title>

    <style>
        body {font-family: Poppins, Arial;background:#eef6f3;padding:20px;}
        .container {max-width:1100px;margin:auto;background:#fff;padding:25px;border-radius:10px;
            box-shadow:0 2px 12px rgba(0,0,0,0.1);}
        h2 {text-align:center;margin-bottom:20px;}

        table {width:100%;border-collapse:collapse;margin-top:20px;}
        th,td {padding:12px;border-bottom:1px solid #ccc;text-align:center;}
        th {background:#2e7d32;color:white;}

        .pending {color:#d68100;font-weight:bold;}
        .approved {color:#2e8b57;font-weight:bold;}
        .rejected {color:#d63031;font-weight:bold;}

        .btn {
            padding:7px 12px;
            border:none;
            border-radius:5px;
            cursor:pointer;
            color:white;
        }
        .approve {background:#28a745;}
        .reject {background:#d9534f;}
    </style>

</head>
<body>

<div class="container">
    <h2>Manage Refund Requests</h2>

    <table>
        <tr>
            <th>Refund ID</th>
            <th>User</th>
            <th>Booking ID</th>
            <th>Payment ID</th>
            <th>Amount</th>
            <th>Payment Mode</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Request Date</th>
            <th>Action</th>
        </tr>

        <?php while($row = $refunds->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['refund_id'] ?></td>
            <td><?= $row['user_name'] ?></td>
            <td><?= $row['booking_id'] ?></td>
            <td><?= $row['payment_id'] ?></td>
            <td>₹<?= $row['amount'] ?></td>
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

            <td>
                <?php if ($row['status'] == "Pending") { ?>
                    <a href="?approve=<?= $row['refund_id'] ?>" 
                       class="btn approve"
                       onclick="return confirm('Approve this refund?')">Approve</a>

                    <a href="?reject=<?= $row['refund_id'] ?>" 
                       class="btn reject"
                       onclick="return confirm('Reject this refund?')">Reject</a>
                <?php } else { ?>
                    <b>—</b>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>
