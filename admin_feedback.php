<?php
$conn = new mysqli("localhost", "root", "", "shantubag_db", 3307);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$sql = "SELECT * FROM feedback ORDER BY created_at DESC";
$result = $conn->query($sql);

function ratingToStars($ratingText) {
    $map = ['Poor'=>1,'Average'=>2,'Good'=>3,'Very Good'=>4,'Excellent'=>5];
    return str_repeat('⭐', $map[$ratingText] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Feedback</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    background: #fdf2e9;
    font-family: Arial, sans-serif;
    margin: 0;
}

.header {
    background: #f8e0e6;
    padding: 40px;
    text-align: center;
    color: #7c0a02;
    border-bottom: 3px solid #d29aa6;
}

.header h1 { margin: 0; font-size: 38px; font-weight: bold; }
.header p { margin: 5px 0; font-size: 15px; }

.title {
    text-align: center;
    font-size: 26px;
    margin-top: 25px;
    color: #7c0a02;
    font-weight: bold;
}

.info {
    margin-left: 40px;
    margin-top: 20px;
    color: #7c0a02;
    font-size: 14px;
}

.table-container {
    width: 92%;
    margin: 30px auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

th {
    background: #f3d4df;
    border: 1px solid #d09aa4;
    color: #7c0a02;
    padding: 10px;
    font-weight: bold;
}

td {
    border: 1px solid #d09aa4;
    padding: 10px;
}

tr:nth-child(even) {
    background: #fdeef4;
}

footer {
    text-align: center;
    margin: 40px 0;
    color: #7c0a02;
    font-size: 14px;
}

.controls {
    margin: 20px 40px;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: center;
}

input[type=date] {
    padding: 8px;
    border: 1px solid #c98b9a;
    border-radius: 6px;
}

.btn {
    background: #b43b6b;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
}

.btn:hover {
    background: #d1558a;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h1>SHANTUBAG AGRO PORTAL</h1>
    <p>At Post: Kedambe, Medha Bondarwadi Road, Jaoli, Maharashtra 415012</p>
    <p>Call: +91 9309906110 / +91 9860549846</p>
    <p>Email: shantubag@gmail.com</p>
</div>

<!-- TITLE -->
<div class="title">Manage Feedback</div>

<!-- DOWNLOAD + DATE FILTER -->
<div class="controls">
    <button class="btn" onclick="downloadPDF()">📥 Download Full Report</button>

    <label>From:</label>
    <input type="date" id="fromDate">

    <label>To:</label>
    <input type="date" id="toDate">

    <button class="btn" onclick="downloadDatePDF()">📅 Download Date-wise</button>
    <button class="btn" onclick="print()"> print</button>
</div>

<!-- GENERATED INFO -->
<div class="info">
    Generated on: <?= date("m/d/Y, h:i:s A") ?>
</div>

<!-- TABLE -->
<div class="table-container">
<table id="feedbackTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>Email</th>
            <th>Rating</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row["id"] ?></td>
                <td><?= htmlspecialchars($row["name"]) ?></td>
                <td><?= htmlspecialchars($row["email"]) ?></td>
                <td><?= ratingToStars($row["rating"]) ?></td>
                <td><?= nl2br(htmlspecialchars($row["message"])) ?></td>
                <td><?= $row["created_at"] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>

<footer>© Shantubag Agro Portal — Auto-generated Feedback Report</footer>

<!-- PDF JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
function reportHeader(doc) {
    let pink = "#b43b6b";

    doc.setTextColor(pink);
    doc.setFontSize(22);
    doc.text("SHANTUBAG AGRO PORTAL", 105, 15, { align: "center" });

    doc.setFontSize(12);
    doc.text("At Post: Kedambe, Medha Bondarwadi Road, Jaoli, Maharashtra 415012", 105, 23, { align: "center" });
    doc.text("Call: +91 9309906110 / +91 9860549846", 105, 30, { align: "center" });
    doc.text("Email: shantubag@gmail.com", 105, 37, { align: "center" });

    doc.setFontSize(16);
    doc.text("Feedback Report", 105, 48, { align: "center" });

    doc.setFontSize(11);
    doc.text("Generated on: " + new Date().toLocaleString(), 105, 56, { align: "left" });
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    reportHeader(doc);

    doc.autoTable({
        html: "#feedbackTable",
        startY: 62,
        theme: "grid",
        headStyles: { fillColor: "#f3d4df", textColor: "#7c0a02" },
        alternateRowStyles: { fillColor: "#fdeef4" },
        tableLineColor: "#b26282ff",
        tableLineWidth: 0.3
    });

    doc.save("Feedback_Report.pdf");
}

function downloadDatePDF() {
    const from = document.getElementById("fromDate").value;
    const to = document.getElementById("toDate").value;

    if (!from || !to) return alert("Select both dates");

    const table = document.getElementById("feedbackTable");
    const rows = [];
    const headers = [];

    table.querySelectorAll("thead th").forEach(th => headers.push(th.innerText));

    table.querySelectorAll("tbody tr").forEach(tr => {
        const cells = tr.querySelectorAll("td");
        const date = new Date(cells[5].innerText).toISOString().split("T")[0];

        if (date >= from && date <= to) {
            const row = [];
            cells.forEach(td => row.push(td.innerText));
            rows.push(row);
        }
    });

    if (rows.length === 0) return alert("No records found for selected date");

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    reportHeader(doc);

    doc.setFontSize(13);
    doc.setTextColor("#b43b6b");
    doc.text(`Filter: ${from} to ${to}`, 105, 65, { align: "left" });

    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 72,
        theme: "grid",
        headStyles: { fillColor: "#f3d4df", textColor: "#7c0a02" },
        alternateRowStyles: { fillColor: "#fdeef4" },
        tableLineColor: "#b43b6b",
        tableLineWidth: 0.3
    });

    doc.save("Feedback_Report_Datewise.pdf");
}
</script>

</body>
</html>
