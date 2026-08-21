<?php
// admin_manage_cancellations.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shantubag_db";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// ✅ Handle refund approval/rejection
if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action == "approve") {
        $sql = "UPDATE booking SET payment_status='Refund Approved', status='Refunded' WHERE id=$id";
    } elseif ($action == "reject") {
        $sql = "UPDATE booking SET payment_status='Refund Rejected' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Refund status updated successfully!'); window.location='admin_manage_cancellations.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// ✅ Fetch cancelled bookings
$sql = "SELECT id, name, booking_type, total_price, cancel_reason, cancel_comments, status, payment_status, created_at
        FROM bookings
        WHERE status='Cancelled' OR payment_status LIKE '%Refund%'
        ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Cancellations - Admin</title>
<style>
  body { font-family: Arial; background: #f4f9f9; padding: 20px; }
  h2 { text-align: center; color: #0073e6; }
  table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
  th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
  th { background: #0073e6; color: #fff; }
  .approve { background: #4caf50; color: white; border: none; padding: 5px 10px; border-radius: 4px; }
  .reject { background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 4px; }
</style>
</head>
<body>

<h2>Manage Cancellations & Refunds</h2>

<?php if ($result->num_rows > 0) { ?>
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Booking Type</th>
    <th>Total (₹)</th>
    <th>Reason</th>
    <th>Comments</th>
    <th>Status</th>
    <th>Payment Status</th>
    <th>Action</th>
  </tr>

  <?php while ($row = $result->fetch_assoc()) { ?>
  <tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['booking_type']; ?></td>
    <td><?php echo $row['total_price']; ?></td>
    <td><?php echo $row['cancel_reason']; ?></td>
    <td><?php echo $row['cancel_comments']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td><?php echo $row['payment_status']; ?></td>
    <td>
      <?php if ($row['payment_status'] == 'Refund Requested') { ?>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
          <button type="submit" name="action" value="approve" class="approve">Approve</button>
          <button type="submit" name="action" value="reject" class="reject">Reject</button>
        </form>
      <?php } else { echo '-'; } ?>
    </td>
  </tr>
  <?php } ?>
</table>

<?php } else { ?>
<p style="text-align:center;">No cancellations found.</p>
<?php } ?>

</body>
</html>
