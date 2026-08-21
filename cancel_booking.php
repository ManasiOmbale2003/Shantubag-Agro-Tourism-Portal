<?php
// Start session and connect to DB
session_start();

$conn = new mysqli("localhost", "root", "", "shantubag_db", 3307);
if ($conn->connect_error) {
    die("❌ DB Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookingId = trim($_POST['id'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $reason    = trim($_POST['reason'] ?? '');

    if (!empty($bookingId) && !empty($email)) {
        // ✅ Check if booking exists
        $stmt = $conn->prepare("SELECT * FROM booking WHERE id = ? AND email = ?");
        if (!$stmt) {
            die("❌ SQL Error: " . $conn->error);
        }
        $stmt->bind_param("ss", $bookingId, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // ✅ Update booking status to Cancelled
            $update = $conn->prepare("UPDATE booking SET status = 'Cancelled', cancel_reason = ? WHERE id = ?");
            if (!$update) {
                die("❌ SQL Error (Update): " . $conn->error);
            }
            $update->bind_param("ss", $reason, $bookingId);
            if ($update->execute()) {
                $success = "✅ Booking has been cancelled successfully.";
            } else {
                $error = "❌ Error cancelling booking: " . $update->error;
            }
            $update->close();
        } else {
            $error = "❌ No booking found with that Booking ID and Email.";
        }
        $stmt->close();
    } else {
        $error = "❌ Please enter both Booking ID and Email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cancel Booking - Shantubag</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="container mt-5" style="max-width: 600px;">
  <h2 class="mb-4 text-center">❌ Cancel Your Booking</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label for="booking_id" class="form-label">Booking ID</label>
      <input type="text" class="form-control" id="booking_id" name="booking_id" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Registered Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="mb-3">
      <label for="reason" class="form-label">Reason for Cancellation</label>
      <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="(Optional)"></textarea>
    </div>

    <button type="submit" class="btn btn-danger w-100">Cancel Booking</button>
  </form>

  <div class="text-center mt-3">
    <a href="index.php" class="btn btn-link">← Back to Home</a>
  </div>
</div>

</body>
</html>
