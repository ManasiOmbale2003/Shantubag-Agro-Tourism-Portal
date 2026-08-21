<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include("db_connect.php");

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// ===============================
// APPROVE REFUND
// ===============================
if (isset($_GET['approve'])) {

    $refund_id = intval($_GET['approve']);

    $sql = "SELECT r.*, b.email, b.name 
            FROM refunds r 
            JOIN booking b ON b.id = r.booking_id
            WHERE r.refund_id = $refund_id LIMIT 1";

    $ref = $conn->query($sql)->fetch_assoc();

    $stmt = $conn->prepare("UPDATE refunds SET status='Approved' WHERE refund_id=?");
    $stmt->bind_param("i", $refund_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Refund Approved Successfully'); window.location='admin_manage_refunds.php';</script>";
    exit;
}

// ===============================
// REJECT REFUND
// ===============================
if (isset($_GET['reject'])) {

    $refund_id = intval($_GET['reject']);

    $stmt = $conn->prepare("UPDATE refunds SET status='Rejected' WHERE refund_id=?");
    $stmt->bind_param("i", $refund_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Refund Rejected Successfully'); window.location='admin_manage_refunds.php';</script>";
    exit;
}

// ===============================
// DATE FILTER
// ===============================
$from_date = $_GET['from_date'] ?? '';
$to_date   = $_GET['to_date'] ?? '';

$where = "";
if ($from_date && $to_date) {
    $where = "WHERE DATE(request_date) BETWEEN '$from_date' AND '$to_date'";
}

$sql = "SELECT * FROM refunds $where ORDER BY refund_id DESC";
$refunds = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
<title>Refund Report | Shantubag Agro Portal</title>

<style>
body { font-family: "Poppins", sans-serif; background:#f8f2fa; padding:20px; }
table { width:100%; border-collapse:collapse; background:white; margin-top:15px; }
th { background:#b30059; color:white; padding:10px; }
td { padding:8px; border:1px solid #bc6bb7ff; }
.btn { padding:5px 10px; border-radius:5px; text-decoration:none; color:white; }
.btn-success { background:green; }
.btn-danger { background:red; }
.filter-box { background:white; padding:10px; border-radius:6px; margin-bottom:15px; }

.print-btn {
    background:#008CBA;
    color:white;
    padding:10px 18px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    margin-top:10px;
}
</style>

</head>
<body>

<!-- HEADER -->
<div style="text-align:center;">
    <h1 style="margin:0;font-size:26px;">SHANTUBAG AGRO PORTAL</h1>
    <p style="margin:0;">At Post: Kedambe, Tal: Jaoli, Dist: Satara 415012</p>
    <p style="margin:0;">Call: +91 9309906110 / +91 9860549846</p>
    <p style="margin:0;">Email: shantubaug@gmail.com</p>
</div>

<hr>

<h2 style="text-align:center;">Refund Requests Manage</h2>

<div class="filter-box">
<form method="GET">
    <label><b>From:</b></label>
    <input type="date" name="from_date" value="<?= $from_date ?>">
    <label><b>To:</b></label>
    <input type="date" name="to_date" value="<?= $to_date ?>">
    <button type="submit">Filter</button>
</form>
</div>

<!-- BUTTONS -->
<button id="downloadPDF"
        style="background:#b30059;color:white;padding:10px 18px;border:none;border-radius:5px;cursor:pointer;">
    Download PDF
</button>

<button onclick="printReport()" class="print-btn">Print Report</button>

<button onclick="goBack()" class="print-btn" style="background:#444;">Back to Dashboard</button>

<table id="refundTable">
<tr>
    <th>ID</th>
    <th>User Name</th>
    <th>Booking ID</th>
    <th>Amount</th>
    <th>Payment Mode</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Date</th>
    <th class="hidePDF">Action</th>
</tr>

<?php while ($row = $refunds->fetch_assoc()) { ?>
<tr>
    <td><?= $row['refund_id'] ?></td>
    <td><?= $row['user_name'] ?></td>
    <td><?= $row['booking_id'] ?></td>
    <td><?= $row['amount'] ?></td>
    <td><?= $row['payment_mode'] ?></td>
    <td><?= $row['reason'] ?></td>
    <td><?= $row['status'] ?></td>
    <td><?= $row['request_date'] ?></td>

    <td class="hidePDF">
        <a href="?approve=<?= $row['refund_id']; ?>" class="btn btn-success">Approve</a>
        <a href="?reject=<?= $row['refund_id']; ?>" class="btn btn-danger">Reject</a>
    </td>
</tr>
<?php } ?>
</table>

<!-- JS PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
// PRINT BUTTON
function printReport() {
    window.print();
}

// BACK TO DASHBOARD
function goBack() {
    window.location.href = "AdminDashboard.php";
}

document.getElementById("downloadPDF").addEventListener("click", function () {

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');

    // HEADER BAR
    doc.setFillColor(255, 220, 235);
    doc.rect(0, 0, 600, 60, "F");

    doc.setFontSize(18);
    doc.setTextColor(120, 0, 60);
    doc.text("SHANTUBAG AGRO PORTAL - Refund Report", 300, 25, { align: "center" });

    doc.setFontSize(10);
    doc.text("Contact: +91 9309906110 / +91 9860549846 | Email: shantubaug@gmail.com", 300, 45, { align: "center" });

    // TABLE EXPORT
    doc.autoTable({
        html: "#refundTable",
        startY: 80,
        headStyles: { fillColor: [179, 0, 89] },
        styles: { fontSize: 9, cellPadding: 4 },

        // HIDE ACTION COLUMN
        didParseCell: function (data) {
            if (data.cell.raw && data.cell.raw.classList.contains("hidePDF")) {
                data.cell.text = "";
            }
        },

        columnStyles: {
            0: { cellWidth: 40 },
            1: { cellWidth: 80 },
            2: { cellWidth: 55 },
            3: { cellWidth: 55 },
            4: { cellWidth: 60 },
            5: { cellWidth: 110 },
            6: { cellWidth: 55 },
            7: { cellWidth: 70 }
        }
    });

    doc.save("Refund_Report.pdf");
});
</script>

</body>
</html>
