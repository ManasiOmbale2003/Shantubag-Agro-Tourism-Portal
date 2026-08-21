<?php
// payment_history.php
session_start();
include "db_connect.php";

// --- 1) Make sure user is logged in and we have an identifier
// Prefer using id if you have it, otherwise use email.
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    // Not logged in — redirect to login page (adjust path as needed)
    header("Location: user_login.php");
    exit();
}
$user_email = $_SESSION['email'];

// --- 2) Prepare and execute a safe query to fetch this user's payments
// We join payments -> booking and filter by booking.email. We also ensure
// we reference the correct column names and table aliases.
$sql = "
    SELECT
        p.id AS payment_id,
        p.transaction_id,
        p.amount,
        p.payment_mode,
        p.status AS payment_status,
        p.receipt,
        p.created_at AS payment_date,
        b.id AS booking_id,
        b.booking_type,
        b.checkin,
        b.checkout
    FROM payments p
    JOIN booking b ON p.booking_id = b.id
    WHERE b.email = ?
    -- optional: exclude bookings with Pending status (uncomment if desired)
    -- AND b.status <> 'Pending'
    ORDER BY p.created_at DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    // Prepare failed — show debug-friendly message (remove in production)
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>My Payments</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">🧾 My Payment History</h2>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Transaction ID</th>
                    <th>Booking ID</th>
                    <th>Booking Type</th>
                    <th>Amount (₹)</th>
                    <th>Payment Mode</th>
                    <th>Status</th>
                    <th>Receipt</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    $receipt = !empty($row['receipt']) ? htmlspecialchars($row['receipt']) : '';
                    $receiptBtn = $receipt ? "<a href='{$receipt}' class='btn btn-sm btn-outline-primary' download>Download</a>" : '—';
                    echo "<tr>
                            <td>{$i}</td>
                            <td>" . htmlspecialchars($row['transaction_id'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['booking_id']) . "</td>
                            <td>" . htmlspecialchars($row['booking_type'] ?? '-') . "</td>
                            <td>₹" . number_format($row['amount'], 2) . "</td>
                            <td>" . htmlspecialchars($row['payment_mode'] ?? '-') . "</td>
                            <td>" . htmlspecialchars($row['payment_status']) . "</td>
                            <td>{$receiptBtn}</td>
                            <td>" . htmlspecialchars($row['payment_date']) . "</td>
                          </tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='9' class='text-danger'>No payment records found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <a href="user_Dashboard.php" class="btn btn-success mt-3">← Back to Dashboard</a>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
