<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include "db_connect.php";

$res = $conn->query("SELECT * FROM visitors ORDER BY visit_time DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Website Visitors</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f9f4;
    margin: 0;
    padding: 0;
  }
  header {
    background: #2c3e50;
    color: white;
    text-align: center;
    padding: 20px;
  }
  h1 {
    margin: 0;
  }
  .container {
    max-width: 1100px;
    margin: 30px auto;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }
  h2 {
    color: #2c3e50;
    text-align: center;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
  }
  th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
  }
  th {
    background: #16a085;
    color: white;
  }
  tr:hover {
    background: #f1f1f1;
  }
  .controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin: 15px 0;
  }
  .btn {
    background: #16a085;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
  }
  .btn:hover {
    background: #1abc9c;
  }
  input[type="date"] {
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
  }
  .back-btn {
    background: #2e7d32;
    color: white;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 6px;
    margin-bottom: 10px;
    display: inline-block;
  }
  .back-btn:hover {
    background: #1b5e20;
  }
</style>
</head>
<body>

<header>
  <h1>🌿 Shantubag Agro Portal</h1>
  <p>Admin Panel - Visitor Tracking</p>
</header>

<div class="container">
  <a href="AdminDashboard.php" class="back-btn">⬅ Back</a>
  <h2>Website Visitors</h2>

  <div class="controls">
    <button class="btn" onclick="generatePDF()">📄 Download All Visitors PDF</button>

    <label>From:</label>
    <input type="date" id="fromDate">
    <label>To:</label>
    <input type="date" id="toDate">
    <button class="btn" onclick="generateDatewisePDF()">📅 Download Date-wise PDF</button>
  </div>

  <table id="visitorTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>IP Address</th>
        <th>Browser</th>
        <th>Page Visited</th>
        <th>Visit Time</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['ip_address']) ?></td>
        <td><?= htmlspecialchars($row['user_agent']) ?></td>
        <td><?= htmlspecialchars($row['page_visited']) ?></td>
        <td><?= $row['visit_time'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<footer style="background:#2c3e50;color:white;text-align:center;padding:10px;margin-top:20px;">
  &copy; <?= date("Y"); ?> Shantubag Agro Portal | Admin Visitor Report
</footer>

<!-- ✅ jsPDF & AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
// 📄 Download all visitors as PDF
function generatePDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF("landscape");

  const now = new Date();
  const formattedDate = now.toLocaleDateString();
  const formattedTime = now.toLocaleTimeString();

  doc.setFontSize(18);
  doc.text("Shantubag Agro Portal", 14, 15);
  doc.setFontSize(14);
  doc.text("Website Visitor Report", 14, 25);
  doc.setFontSize(11);
  doc.text("Generated on: " + formattedDate + " at " + formattedTime, 14, 32);

  doc.autoTable({ html: "#visitorTable", startY: 40, theme: "striped" });
  doc.save("Visitor_Report_All.pdf");
}

// 📅 Download visitors between selected dates
function generateDatewisePDF() {
  const fromDate = document.getElementById("fromDate").value;
  const toDate = document.getElementById("toDate").value;

  if (!fromDate || !toDate) {
    alert("⚠ Please select both From and To dates.");
    return;
  }

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF("landscape");

  const formattedDate = new Date().toLocaleDateString();
  const formattedTime = new Date().toLocaleTimeString();

  doc.setFontSize(18);
  doc.text("Shantubag Agro Portal", 14, 15);
  doc.setFontSize(14);
  doc.text("Website Visitor Report (Date-wise)", 14, 25);
  doc.setFontSize(11);
  doc.text("Generated on: " + formattedDate + " at " + formattedTime, 14, 32);
  doc.text(`From: ${fromDate} To: ${toDate}`, 14, 38);

  const table = document.getElementById("visitorTable");
  const headers = [];
  const rows = [];

  table.querySelectorAll("thead tr th").forEach(th => headers.push(th.innerText));
  table.querySelectorAll("tbody tr").forEach(tr => {
    const cells = tr.querySelectorAll("td");
    const visitTime = cells[4].innerText;
    const rowDate = new Date(visitTime).toISOString().split("T")[0];

    if (rowDate >= fromDate && rowDate <= toDate) {
      const row = [];
      cells.forEach(td => row.push(td.innerText));
      rows.push(row);
    }
  });

  if (rows.length === 0) {
    alert("⚠ No visitor records found for the selected date range.");
    return;
  }

  doc.autoTable({
    startY: 45,
    head: [headers],
    body: rows,
    theme: "striped"
  });

  doc.save(`Visitor_Report_${fromDate}_to_${toDate}.pdf`);
}
</script>

</body>
</html>
<?php $conn->close(); ?>
