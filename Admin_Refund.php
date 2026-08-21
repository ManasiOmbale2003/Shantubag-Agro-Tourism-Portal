<?php
include "db_connect.php";

$sql = "SELECT * FROM refund_requests ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Refund Requests</title>
<style>
    body {font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px;}
    h2 {color: #0073e6;}
    table {width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0px 2px 6px rgba(0,0,0,0.1);}
    th, td {padding: 12px 15px; border-bottom: 1px solid #ddd; text-align: left;}
    th {background: #0073e6; color: white;}
    tr:hover {background-color: #f1f1f1;}
    .btn {padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; color: white;}
    .approve {background: #28a745;}
    .reject {background: #dc3545;}
    .approve:hover {background: #218838;}
    .reject:hover {background: #c82333;}
    .no-data {text-align: center; color: gray; padding: 20px;}
    .back-btn {background: #333; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; margin-bottom: 15px; display: inline-block;}
</style>
</head>
<body>

<a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
<h2>Refund Requests</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Total (₹)</th>
    <th>Mode</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['total_price']}</td>
            <td>{$row['payment_mode']}</td>
            <td>{$row['cancel_reason']}</td>
            <td>{$row['status']}</td>
            <td>{$row['created_at']}</td>
            <td>
                <form method='POST' action='update_refund_status.php' style='display:inline-block;'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <input type='hidden' name='email' value='{$row['email']}'>
                    <input type='hidden' name='name' value='{$row['name']}'>
                    <input type='hidden' name='amount' value='{$row['total_price']}'>
                    <button name='approve' class='btn approve'>Approve</button>
                </form>
                <form method='POST' action='update_refund_status.php' style='display:inline-block;'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <button name='reject' class='btn reject'>Reject</button>
                </form>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='9' class='no-data'>No refund requests found.</td></tr>";
}
$conn->close();
?>
</table>

</body>
</html>
