<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Shantubag Agro Portal</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      display: flex;
      background: linear-gradient(135deg, #d7e3fc, #fef6fb);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #0077b6, #0096c7);
      color: white;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 30px;
      box-shadow: 2px 0 10px rgba(0,0,0,0.2);
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 22px;
      letter-spacing: 1px;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 12px 25px;
      text-decoration: none;
      font-size: 16px;
      border-left: 3px solid transparent;
      transition: all 0.3s ease;
    }

    .sidebar a:hover {
      background: rgba(255,255,255,0.1);
      border-left: 3px solid #fff;
      padding-left: 30px;
    }

    /* Main content */
    .main {
      margin-left: 250px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    header {
      background: linear-gradient(90deg, #00b4d8, #48cae4);
      color: white;
      padding: 20px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      padding: 40px;
    }

    .card {
      background: white;
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      position: relative;
      text-decoration: none;
      color: #333;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0px 6px 16px rgba(0,0,0,0.2);
      background: linear-gradient(135deg, #f0f9ff, #e6f7ff);
    }

    .card h3 {
      margin-bottom: 10px;
      font-size: 18px;
      color: #005b96;
    }

    .card p {
      font-size: 14px;
      color: #666;
    }

    .badge {
      position: absolute;
      top: 10px;
      right: 15px;
      background: red;
      color: white;
      padding: 5px 9px;
      border-radius: 50%;
      font-size: 12px;
    }

    footer {
      text-align: center;
      padding: 10px;
      color: #555;
      font-size: 14px;
      background: #f7f9fc;
      margin-top: auto;
    }
  </style>
</head>
<body>

<?php
include("db_connect.php");

$sql_users = "SELECT COUNT(*) AS total_users FROM users"; 
$result_users = $conn->query($sql_users);
$total_users = ($result_users && $result_users->num_rows > 0) 
                ? $result_users->fetch_assoc()['total_users'] 
                : 0;

$sql_notif = "SELECT COUNT(*) AS unread_count FROM notifications WHERE status='unread'";
$result_notif = $conn->query($sql_notif);
$unread_count = ($result_notif && $result_notif->num_rows > 0) 
                ? $result_notif->fetch_assoc()['unread_count'] 
                : 0;
?>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Admin Menu</h2>
  <a href="manage_package.php">📦 Manage Packages</a>
  <a href="manage_booking.php">📑 Manage Bookings</a>
  <a href="manage_user.php">👥 Manage Users (<?php echo $total_users; ?>)</a>
  <a href="manage_payment.php">💳 Manage Payments</a>
  <a href="manage_room.php">🏨 Manage Rooms</a>
  <a href="manage_gallery.php">🖼️ Manage Gallery</a>
  <a href="manage_facilities.php">🏡 Manage Facilities</a>
  <a href="admin_feedback.php">⭐ Manage Feedback</a>
  <a href="manage_visitor.php">👀 Visitors</a>
  <a href="notification.php">
    🔔 Notifications
    <?php if ($unread_count > 0) { ?>
      <span class="badge"><?php echo $unread_count; ?></span>
    <?php } ?>
  </a>
  <a href="refund_form.php">📊 Refund Requests</a>
  <a href="manage_contact.php">📧 Contact</a>
  <a href="logout.php">🚪 Logout</a>
</div>

<!-- Main -->
<div class="main">
  <header>🌾 Admin Dashboard - Shantubag Agro Portal</header>

  <div class="dashboard">
    <a href="manage_package.php" class="card"><h3>📦 Packages</h3><p>Add or edit farm stay packages</p></a>
    <a href="manage_booking.php" class="card"><h3>📑 Bookings</h3><p>View and confirm bookings</p></a>
    <a href="manage_user.php" class="card"><h3>👥 Users (<?php echo $total_users; ?>)</h3><p>View registered users</p></a>
    <a href="manage_payment.php" class="card"><h3>💳 Payments</h3><p>Track and verify transactions</p></a>
    <a href="manage_room.php" class="card"><h3>🏨 Rooms</h3><p>Update room details</p></a>
    <a href="admin_cancel_booking.php" class="card"><h3>📧 Cancel Bookings</h3><p>View cancellation</p></a>
    <a href="manage_gallery.php" class="card"><h3>🖼️ Gallery</h3><p>Manage farm images</p></a>
    <a href="manage_facilities.php" class="card"><h3>🏡 Facilities</h3><p>Update farm facilities</p></a>
    <a href="admin_feedback.php" class="card"><h3>⭐ Feedback</h3><p>Check user feedback</p></a>
    <a href="manage_visitor.php" class="card"><h3>👀 Visitors</h3><p>Visitor details</p></a>
    <a href="notification.php" class="card"><h3>🔔 Notifications</h3><p>Check alerts & messages</p>
      <?php if ($unread_count > 0) { ?><span class="badge"><?php echo $unread_count; ?></span><?php } ?></a>
    <a href="manage_contact.php" class="card"><h3>📧 Contact</h3><p>View inquiries</p></a>
    <a href="logout.php" class="card"><h3>🚪 Logout</h3><p>Sign out</p></a>
  </div>

  <footer>© 2025 Shantubag Agro <Portal></Portal>| Admin Panel</footer>
</div>

</body>
</html>
