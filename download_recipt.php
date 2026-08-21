<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

include("db_connect.php");

if (!isset($_GET['receipt'])) {
    die("❌ Invalid access.");
}

$receipt = $_GET['receipt'];

$stmt = $conn->prepare("SELECT * FROM payments WHERE receipt_number = ?");
$stmt->bind_param("s", $receipt);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("<h3>❌ Receipt not found.</h3>");
}

$row = $result->fetch_assoc();

$html = '
<h2 style="text-align:center; color:green;">Shantubag Agro Tourism</h2>
<p style="text-align:center;">Official Payment Receipt</p>
<hr>
<p><strong>Receipt No:</strong> '.$row['receipt_number'].'</p>
<p><strong>Name:</strong> '.$row['name'].'</p>
<p><strong>Email:</strong> '.$row['email'].'</p>
<p><strong>Booking ID:</strong> '.$row['booking_id'].'</p>
<p><strong>Payment Method:</strong> '.$row['payment_method'].'</p>
<p><strong>Amount Paid:</strong> ₹'.$row['amount'].'</p>
<p><strong>Status:</strong> '.$row['status'].'</p>
<p><strong>Date:</strong> '.$row['payment_date'].'</p>
<hr>
<p style="text-align:center;">✅ Thank you for your payment!</p>
';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait');
$dompdf->render();
$dompdf->stream("Receipt_" . $row['receipt_number'] . ".pdf", ["Attachment" => true]);
exit;
?>
