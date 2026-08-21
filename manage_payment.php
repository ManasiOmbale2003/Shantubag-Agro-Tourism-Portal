<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shantubag_db";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Delete payment
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $conn->query("DELETE FROM payments WHERE payment_id = '$delete_id'");
    echo "<script>alert('Payment deleted successfully!');</script>";
}

// Filters
$from_date = $_GET['from_date'] ?? '';
$to_date   = $_GET['to_date'] ?? '';
$status    = $_GET['status'] ?? 'ALL';

$where = "WHERE 1";

// Date filter
if (!empty($from_date) && !empty($to_date)) {
    $where .= " AND payment_date BETWEEN '$from_date' AND '$to_date'";
}

// Status filter
if ($status !== "ALL") {
    $where .= " AND status = '$status'";
}

$result = $conn->query("SELECT * FROM payments $where ORDER BY payment_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Payments Shantubag Agro Portal</title>

<!-- jsPDF + AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<style>
body {
    font-family: "Poppins", sans-serif;
    background: #ffe4ef;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 1100px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #b30059;
    margin-bottom: 20px;
}
button, .btn {
    cursor: pointer;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    transition: 0.3s;
    font-weight: 500;
}
.btn-back { background: #d81b60; color: white; }
.btn-filter { background: #ec407a; color: white; }
.btn-reset { background: #f8bbd0; color: black; }
.btn-print { background: #c2185b; color: white; }
.btn-pdf { background: #ad1457; color: white; }

.status-paid { color: green; font-weight: bold; }
.status-unpaid { color: red; font-weight: bold; }

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.table th {
    background: #f48fb1;
    color: #4a0033;
    padding: 10px;
}
.table td {
    border-bottom: 1px solid #fccde1;
    padding: 10px;
    text-align: center;
}
.table tr:hover {
    background-color: #ffe4ef;
}

.filter-box {
    text-align: center;
    margin-bottom: 20px;
}

input[type="date"], select {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

@media print {
    #actionButtons, .hide-pdf { display: none !important; }
}
</style>
</head>

<body>

<div class="container">
    <div id="actionButtons">
        <button class="btn btn-back" onclick="window.location.href='AdminDashboard.php'">← Back</button>

        <h2>💳 Manage Payments</h2>

        <div class="filter-box">
            <form method="get">

                From: <input type="date" name="from_date" value="<?= $from_date ?>">
                To: <input type="date" name="to_date" value="<?= $to_date ?>">

                Status:
                <select name="status">
                    <option value="ALL" <?= $status=="ALL" ? "selected" : "" ?>>ALL</option>
                    <option value="Paid" <?= $status=="Paid" ? "selected" : "" ?>>Paid</option>
                    <option value="Unpaid" <?= $status=="Unpaid" ? "selected" : "" ?>>Unpaid</option>
                </select>

                <button type="submit" class="btn btn-filter">Filter</button>
                <a href="manage_payment.php" class="btn btn-reset">Reset</a>
            </form>

            <button onclick="window.print()" class="btn btn-print">🖨 Print</button>
            <button onclick="downloadPDF()" class="btn btn-pdf">📄 Download PDF</button>
        </div>
    </div>

    <div style="text-align:right; font-size:13px; color:#555; margin-bottom:8px;">
        Generated on: <?= date("d/m/Y, h:i:s A") ?>
    </div>

    <?php if ($from_date && $to_date): ?>
        <div style="text-align:right; font-size:14px; color:#444; margin-bottom:10px;">
            Date Filter: <?= $from_date ?> → <?= $to_date ?>
        </div>
    <?php endif; ?>

    <?php if ($status !== "ALL"): ?>
        <div style="text-align:right; font-size:14px; color:#444; margin-bottom:15px;">
            Status: <?= $status ?>
        </div>
    <?php endif; ?>

    <table class="table" id="paymentTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Booking ID</th>
                <th>Amount</th>
                <th>Mode</th>
                <th>Status</th>
                <th class="hide-pdf">Receipt</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['payment_id'] ?></td>
                    <td><?= $row['user_name'] ?></td>
                    <td><?= $row['booking_id'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td><?= $row['payment_mode'] ?></td>

                    <td class="<?= $row['status']=="Paid" ? "status-paid" : "status-unpaid" ?>">
                        <?= $row['status'] ?>
                    </td>

                    <td class="hide-pdf">
                        <a href="<?= $row['receipt'] ?>" target="_blank">View</a>
                    </td>

                    <td><?= $row['payment_date'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align:center;color:red;">No payment records found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function downloadPDF() {

    document.getElementById("actionButtons").style.display = "none";
    document.querySelectorAll(".hide-pdf").forEach(el => el.style.display = "none");

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    let today = new Date().toLocaleString();

    // 🔴 Pink Header Box
    doc.setFillColor(255, 220, 235);
    doc.rect(0, 0, 220, 48, "F");

    // 🔴 Main Heading
    doc.setTextColor(105, 0, 60);
    doc.setFontSize(18);
    doc.text("SHANTUBAG AGRO PORTAL", 105, 12, { align: "center" });

    // Subtitle
    doc.setFontSize(12);
    doc.text("Payments Report", 105, 20, { align: "center" });

    // 🔶 Address & Contact Centered
    doc.setFontSize(10);
    doc.text("Shantubag Agro Portal,At.Post.Kedambe, Tal-Jawli, Satara, MH 415012", 105, 26, { align: "center" });
    doc.text("Contact:+91 9309906110 / +91 9860549846 |  Email: shantubaug@gmail.com", 105, 32, { align: "center" });

    // Generated Time
    doc.text("Generated on: " + today, 105, 38, { align: "center" });

    // 🔽 Filter Date – Under Header
    <?php if ($from_date && $to_date): ?>
        doc.text("Date Filter: <?= $from_date ?> to <?= $to_date ?>", 105, 44, { align: "center" });
    <?php endif; ?>

    // 🔽 Status Filter
    <?php if ($status !== "ALL"): ?>
        doc.text("Status: <?= $status ?>", 105, 50, { align: "center" });
    <?php endif; ?>

    // TABLE
    doc.autoTable({
        html: "#paymentTable",
        startY: 58,
        headStyles: { fillColor: [244, 143, 177] },
        styles: { font: "helvetica", cellPadding: 2 }
    });

    doc.save("Payments_Report.pdf");

    document.getElementById("actionButtons").style.display = "block";
    document.querySelectorAll(".hide-pdf").forEach(el => el.style.display = "block");
}
</script>

</body>
</html>
