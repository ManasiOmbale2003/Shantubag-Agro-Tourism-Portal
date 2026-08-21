<?php
session_start();
include "db_connect.php";

$filter = isset($_GET['filter']) ? $_GET['filter'] : "ALL";
$type   = isset($_GET['type']) ? $_GET['type'] : "ALL";

$query = "SELECT * FROM booking WHERE 1 ";

if ($filter == "PAID") $query .= "AND payment_status='Paid' ";
if ($filter == "UNPAID") $query .= "AND payment_status!='Paid' ";

if ($type == "ROOM") $query .= "AND booking_type='Room' ";
if ($type == "PACKAGE") $query .= "AND booking_type='Package' ";

$query .= "ORDER BY id DESC";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Bookings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#fde3ee; font-family:'Segoe UI'; }
.container-box {
    width:95%; margin:30px auto; background:white;
    padding:25px; border-radius:20px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
.page-title {
    background:#ffb6d9; padding:15px; border-radius:10px;
    text-align:center; color:#800040; font-size:28px; font-weight:700;
}

/* FILTER BUTTONS */
.filter-btn, .type-btn {
    padding:8px 14px; border-radius:6px; border:0;
    margin-right:5px; font-weight:600; color:white;
    background:#ff76b3; text-decoration:none;
}
.filter-btn.active, .type-btn.active { background:#cc0066; }

/* TABLE DESIGN */
.table thead th {
    background:#ff9ac2;
    color:#6d0040;
    text-align:center;
    font-weight:700;
    padding:12px;
    font-size:15px;
}

.table tbody tr:nth-child(odd) {
    background:#fff7f0;
}

.table tbody tr:nth-child(even) {
    background:#faf1e5;
}

.table td {
    text-align:center;
    padding:12px;
    font-size:14px;
    color:#333;
}

.status-paid { color:green; font-weight:bold; }
.status-unpaid { color:red; font-weight:bold; }

.hide-pdf { }
</style>
</head>
<body>

<div class="container-box">

<div class="page-title">Manage Booking</div>

<!-- FILTERS -->
<div class="my-3">
    <a href="?filter=ALL&type=<?= $type ?>" class="filter-btn <?= $filter=='ALL'?'active':'' ?>">ALL</a>
    <a href="?filter=PAID&type=<?= $type ?>" class="filter-btn <?= $filter=='PAID'?'active':'' ?>">PAID</a>
    <a href="?filter=UNPAID&type=<?= $type ?>" class="filter-btn <?= $filter=='UNPAID'?'active':'' ?>">UNPAID</a>
</div>

<!-- TYPE FILTER -->
<div class="my-3">
    <a href="?filter=<?= $filter ?>&type=ALL" class="type-btn <?= $type=='ALL'?'active':'' ?>">ALL BOOKINGS</a>
    <a href="?filter=<?= $filter ?>&type=ROOM" class="type-btn <?= $type=='ROOM'?'active':'' ?>">ROOM</a>
    <a href="?filter=<?= $filter ?>&type=PACKAGE" class="type-btn <?= $type=='PACKAGE'?'active':'' ?>">PACKAGES</a>
</div>

<!-- DATE FILTER -->
<div class="my-3 hide-pdf">
    <label>From:</label>
    <input type="date" id="from_date">
    <label>To:</label>
    <input type="date" id="to_date">
    <button onclick="downloadPDF()" class="btn btn-danger">Download PDF</button>
</div>

<!-- TABLE -->
<div class="table-responsive">
<table class="table table-bordered" id="bookingTable">
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Booking Type</th>
<th>Guests</th>
<th>Check-in</th>
<th>Check-out</th>
<th>Total Price</th>
<th>Status</th>
<th class="hide-pdf">Action</th>
</tr>
</thead>

<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['booking_type'] ?></td>
<td><?= $row['guests'] ?></td>
<td><?= $row['checkin'] ?></td>
<td><?= $row['checkout'] ?></td>
<td>₹<?= number_format($row['total_price']) ?></td>

<td class="<?= $row['payment_status']=='Paid'?'status-paid':'status-unpaid' ?>">
<?= $row['payment_status'] ?>
</td>

<td class="hide-pdf">
<a href="manage_booking.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</div>

<!-- PDF LIBRARIES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
function downloadPDF() {

    document.querySelectorAll(".hide-pdf").forEach(e => e.style.display="none");

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    let from = document.getElementById("from_date").value;
    let to   = document.getElementById("to_date").value;

    // HEADER PINK BOX
    doc.setFillColor(255, 220, 235);
    doc.rect(0, 0, 220, 38, "F");

    doc.setTextColor(105, 0, 60);
    doc.setFontSize(18);
    doc.text("SHANTUBAG AGRO PORTAL", 105, 12, { align: "center" });

    doc.setFontSize(12);
    doc.text("Booking Report", 105, 20, { align: "center" });

    if (from && to) {
        doc.text(`Date Filter: ${from} to ${to}`, 105, 28, { align: "center" });
    }

    doc.setFontSize(10);
    doc.text("Contact:+91 9309906110 / +91 9860549846 | Email: shantubaug@gmail.com",
        105, 32, { align: "center" });

    doc.autoTable({
    html: "#bookingTable",
    startY: 50,
    headStyles: { fillColor: [255,150,200], fontSize: 11 },
    styles: { fontSize: 10, cellPadding: 3 },

    // 🌟 CUSTOM COLUMN WIDTHS
    columnStyles: {
        0: { cellWidth: 15 },   // ID
        1: { cellWidth: 35 },   // Name
        2: { cellWidth: 45 },   // Email
        3: { cellWidth: 30 },   // Booking Type
        4: { cellWidth: 18 },   // Guests
        5: { cellWidth: 28 },   // Check-in
        6: { cellWidth: 28 },   // Check-out
        7: { cellWidth: 25 },   // Total Price
        8: { cellWidth: 22 }    // Status
    },

    tableWidth: "auto"
});


    doc.save("Booking_Report.pdf");

    document.querySelectorAll(".hide-pdf").forEach(e => e.style.display="block");
}
</script>

</body>
</html>
