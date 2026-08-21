<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['id']) || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$user_name = $_SESSION['fullname'] ?? '';
$user_email = $_SESSION['email'];

// Fetch bookings + payments for auto-fill
// NOTE: booking table has primary key `id` and user info is stored in `email` (from your schema screenshot).
$sql = "SELECT 
            b.id AS booking_id,
            b.checkin,
            b.checkout,
            p.payment_id,
            p.amount,
            p.payment_mode
        FROM booking b
        LEFT JOIN payments p ON b.id = p.booking_id
        WHERE b.email = ?
        ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL ERROR (prepare): " . $conn->error);
}

$stmt->bind_param("s", $user_email);
if (!$stmt->execute()) {
    die("SQL ERROR (execute): " . $stmt->error);
}

$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Refund Request</title>

    <style>
        body {font-family:Arial;background:#f0f8f5;padding:20px;}
        .card {max-width:600px;background:white;margin:30px auto;padding:20px;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.1);}
        label {display:block;margin-top:10px;font-weight:600;}
        input,select,textarea{width:100%;padding:8px;margin:6px 0;border-radius:6px;border:1px solid #ddd;box-sizing:border-box;}
        button{background:#28a745;color:white;padding:10px;border:0;border-radius:6px;cursor:pointer;margin-top:12px;}
        small.note{color:#666}
    </style>
</head>
<body>

<div class="card">
    <h2>Refund Request</h2>

    <form method="POST" action="refund_submit.php">

        <label>User Name <small class="note">(readonly)</small></label>
        <input type="text" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" readonly>

        <label>Select Booking</label>
        <select id="booking_select" name="booking_select" required>
            <option value="">-- Select Booking --</option>
            <?php if (!empty($bookings)): ?>
                <?php foreach($bookings as $b): ?>
                    <?php
                      // Prepare a label (date range or id) so user can identify booking
                      $label = "Booking #".$b['booking_id'];
                      if (!empty($b['checkin']) || !empty($b['checkout'])) {
                          $label .= " (".htmlspecialchars($b['checkin'])." to ".htmlspecialchars($b['checkout']).")";
                      }
                    ?>
                    <option 
                        value="<?php echo (int)$b['booking_id']; ?>"
                        data-payment="<?php echo htmlspecialchars($b['payment_id']); ?>"
                        data-amount="<?php echo htmlspecialchars($b['amount']); ?>"
                        data-mode="<?php echo htmlspecialchars($b['payment_mode']); ?>">
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">No bookings found for <?php echo htmlspecialchars($user_email); ?></option>
            <?php endif; ?>
        </select>

        <label>Booking ID</label>
        <input type="text" id="booking_id" name="booking_id" readonly required>

        <label>Payment ID</label>
        <input type="text" id="payment_id" name="payment_id" readonly required>

        <label>Amount Paid (₹)</label>
        <input type="text" id="amount" name="amount" readonly required>

        <label>Payment Mode</label>
        <input type="text" id="payment_mode" name="payment_mode" readonly required>

        <label>Reason for Refund</label>
        <textarea name="reason" required rows="4" placeholder="Describe why you want a refund..."></textarea>

        <button type="submit">Submit Refund</button>

    </form>
</div>

<script>
(function(){
    const bookingSelect = document.getElementById('booking_select');
    const bookingIdInp = document.getElementById('booking_id');
    const paymentIdInp = document.getElementById('payment_id');
    const amountInp = document.getElementById('amount');
    const modeInp = document.getElementById('payment_mode');

    bookingSelect?.addEventListener('change', function(){
        const opt = this.options[this.selectedIndex];
        bookingIdInp.value = opt?.value || '';
        paymentIdInp.value = opt?.dataset.payment || '';
        amountInp.value = opt?.dataset.amount || '';
        modeInp.value = opt?.dataset.mode || '';
    });
})();
</script>

</body>
</html>
