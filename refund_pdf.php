<?php
session_start();
include("db_connect.php");

// FETCH ALL REFUNDS
$refunds = $conn->query("SELECT * FROM refunds ORDER BY refund_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin - Manage Refunds</title>

<!-- jsPDF CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
body {font-family: Poppins, Arial;background:#eef6f3;padding:20px;}
.container {max-width:1100px;margin:auto;background:#fff;padding:25px;border-radius:10px;
    box-shadow:0 2px 12px rgba(0,0,0,0.1);}
h2 {text-align:center;margin-bottom:20px;}
table {width:100%;border-collapse:collapse;margin-top:20px;}
th,td {padding:10px;border-bottom:1px solid #ccc;text-align:center;}
th {background:#2e7d32;color:white;}
.pending {color:#d68100;font-weight:bold;}
.approved {color:#2e8b57;font-weight:bold;}
.rejected {color:#d63031;font-weight:bold;}

.buttons {margin-top:15px;text-align:center;}
.btn{
    padding:10px 15px;border:none;border-radius:6px;cursor:pointer;margin:4px;
    color:white;font-weight:bold;
}
.download{background:#1e88e5;}
.print{background:#6a1b9a;}
.back{background:#2e7d32;}
</style>
</head>
<body>

<div class="container">

    <h2>Manage Refund Requests</h2>

    <!-- DATE SELECT FOR PDF -->
    <div style="text-align:center;margin-bottom:15px;">
        <label><b>Select Date:</b></label>
        <input type="date" id="pdf_date">
        <button class="btn download" onclick="downloadPDF()">Download PDF</button>
    </div>

    <table id="refundTable">
        <tr>
            <th>Refund ID</th>
            <th>User</th>
            <th>Booking ID</th>
            <th>Payment ID</th>
            <th>Amount</th>
            <th>Payment Mode</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Request Date</th>
        </tr>

        <?php while($row = $refunds->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['refund_id'] ?></td>
            <td><?= $row['user_name'] ?></td>
            <td><?= $row['booking_id'] ?></td>
            <td><?= $row['payment_id'] ?></td>
            <td>₹<?= $row['amount'] ?></td>
            <td><?= $row['payment_mode'] ?></td>
            <td><?= $row['reason'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['request_date'] ?></td>
        </tr>
        <?php } ?>
    </table>

    <div class="buttons">
        <button class="btn print" onclick="window.print()">Print</button>
        <a href="AdminDashboard.php"><button class="btn back">Back to Dashboard</button></a>
    </div>

</div>

<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    let doc = new jsPDF();

    let selectedDate = document.getElementById("pdf_date").value;
    if (!selectedDate) {
        alert("Please select a date first!");
        return;
    }

    // DATE & TIME
    let now = new Date();
    let timeString = now.toLocaleTimeString();

    doc.setFontSize(16);
    doc.text("Shantubag Agro Portal - Refund Report", 10, 10);
    doc.setFontSize(12);
    doc.text("Report Date: " + selectedDate, 10, 20);
    doc.text("Generated On (Date & Time): " + now.toISOString().split('T')[0] + " " + timeString, 10, 30);

    let rows = [];
    let table = document.querySelectorAll("#refundTable tr");

    for (let i = 1; i < table.length; i++) {
        let cols = table[i].querySelectorAll("td");

        // Only add rows matching selected date
        if (cols[8].innerText === selectedDate) {
            rows.push([
                cols[0].innerText,
                cols[1].innerText,
                cols[2].innerText,
                cols[3].innerText,
                cols[4].innerText,
                cols[5].innerText,
                cols[6].innerText,
                cols[7].innerText,
            ]);
        }
    }

    if (rows.length === 0) {
        alert("No refund records found for selected date!");
        return;
    }

    doc.autoTable({
        head: [["Refund ID","User","Booking ID","Payment ID","Amount","Mode","Reason","Status"]],
        body: rows,
        startY: 40,
        styles: { fontSize: 8 }
    });

    doc.save("Refund_Report_" + selectedDate + ".pdf");
}
</script>

<!-- AutoTable CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

</body>
</html>
