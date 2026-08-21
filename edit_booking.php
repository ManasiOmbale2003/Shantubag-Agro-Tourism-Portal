<?php
include("db_connect.php");

if (!isset($_GET['id'])) die("❌ Invalid Request");
$id = $_GET['id'];

$result = $conn->query("SELECT * FROM bookings WHERE booking_id=$id");
if ($result->num_rows == 0) die("❌ Booking Not Found");
$booking = $result->fetch_assoc();

$users = $conn->query("SELECT * FROM users");
$packages = $conn->query("SELECT * FROM packages");
$rooms = $conn->query("SELECT * FROM rooms");

// Handle Update
if (isset($_POST['update'])) {
    $user_id = $_POST['user_id'];
    $package_id = $_POST['package_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $status = $_POST['status'];
    $payment_status = $_POST['payment_status'];

    $sql = "UPDATE bookings SET user_id='$user_id', package_id='$package_id', room_id='$room_id',
            check_in='$check_in', check_out='$check_out', status='$status', payment_status='$payment_status'
            WHERE booking_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Booking Updated');window.location='manage_bookings.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h2>Edit Booking</h2>
  <form method="POST">
    <div class="mb-3">
      <label>User</label>
      <select name="user_id" class="form-control">
        <?php while($u=$users->fetch_assoc()){ $sel=($u['user_id']==$booking['user_id'])?"selected":""; echo "<option value='{$u['user_id']}' $sel>{$u['username']}</option>"; } ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Package</label>
      <select name="package_id" class="form-control">
        <?php while($p=$packages->fetch_assoc()){ $sel=($p['package_id']==$booking['package_id'])?"selected":""; echo "<option value='{$p['package_id']}' $sel>{$p['name']}</option>"; } ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Room</label>
      <select name="room_id" class="form-control">
        <?php while($r=$rooms->fetch_assoc()){ $sel=($r['room_id']==$booking['room_id'])?"selected":""; echo "<option value='{$r['room_id']}' $sel>{$r['room_name']}</option>"; } ?>
      </select>
    </div>
    <div class="mb-3"><label>Check-In</label><input type="date" name="check_in" value="<?php echo $booking['check_in']; ?>" class="form-control"></div>
    <div class="mb-3"><label>Check-Out</label><input type="date" name="check_out" value="<?php echo $booking['check_out']; ?>" class="form-control"></div>
    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-control">
        <option <?php if($booking['status']=="Pending") echo "selected"; ?>>Pending</option>
        <option <?php if($booking['status']=="Confirmed") echo "selected"; ?>>Confirmed</option>
        <option <?php if($booking['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Payment Status</label>
      <select name="payment_status" class="form-control">
        <option <?php if($booking['payment_status']=="Unpaid") echo "selected"; ?>>Unpaid</option>
        <option <?php if($booking['payment_status']=="Paid") echo "selected"; ?>>Paid</option>
      </select>
    </div>
    <button type="submit" name="update" class="btn btn-primary">Update</button>
    <a href="manage_bookings.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
