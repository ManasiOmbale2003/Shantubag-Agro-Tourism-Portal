<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit; }
include "db_connect.php";

// Clean text
function cleanText($str) {
    return trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $str));
}

// Date filter
$from_date = cleanText($_GET['from_date'] ?? '');
$to_date   = cleanText($_GET['to_date'] ?? '');

$where = "";
if (!empty($from_date) && !empty($to_date)) {
    $where = "WHERE DATE(created_at) BETWEEN '$from_date' AND '$to_date'";
}

// Fetch notifications
$sql = "SELECT id, message, created_at FROM notifications $where ORDER BY id DESC";
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Notifications Report – Shantubag Agro Portal</title>

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<style>
body {
  font-family: 'Poppins', sans-serif;
  background-color: #fffbe6;
  padding: 30px;
  margin: 0;
  color: #333;
}
.report-container {
  background: #fff;
  padding: 30px 40px;
  border-radius: 12px;
  max-width: 1100px;
  margin: auto;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h2 { text-align: center; color: #1b5e20; font-size: 28px; }
.subtitle { text-align:center; margin-bottom:20px; color:#555; }
.buttons { text-align:right; margin-bottom:15px; }
button {
  background:#1b5e20; color:white; padding:10px 16px;
  border:none; border-radius:6px; margin-left:10px;
}
button:hover { background:#2e7d32; }
table { width:100%; border-collapse:collapse; }
th, td { padding: 12px; border-bottom:1px solid #ccc; }
th { background:#1b5e20; color:white; }
.filter-text {
  text-align:right;
  color:#444;
  font-size:14px;
  letter-spacing: normal !important;
}
.generated { text-align:right; font-size:13px; color:#777; margin-bottom:10px; }
</style>
</head>
<body>

<!-- Date Filter Form -->
<div style="text-align:center; margin-bottom:20px;">
<form method="get">
    From: <input type="date" name="from_date" value="<?= $from_date ?>">
    To: <input type="date" name="to_date" value="<?= $to_date ?>">
    <button type="submit">Filter</button>
</form>
</div>

<div class="report-container">

  <h2>Notifications Report</h2>
  <div class="subtitle">New Bookings & Payments</div>

  <div class="buttons">
    <button onclick="window.print()">🖨️ Print</button>
    <button onclick="downloadPDF()">📄 Download PDF</button>
  </div>

  <div class="generated">Generated on: <?= date("d/m/Y, h:i:s A") ?></div>

  <?php if ($from_date && $to_date): ?>
  <div class="filter-text">Filter: <?= $from_date ?> → <?= $to_date ?></div>
  <?php endif; ?>

  <table id="notificationTable">
      <thead>
          <tr>
              <th>ID</th>
              <th>Message</th>
              <th>Date</th>
          </tr>
      </thead>
      <tbody>
      <?php if ($res->num_rows > 0): ?>
          <?php while ($row = $res->fetch_assoc()): ?>
              <?php
              $clean_message = cleanText($row['message']);
              $clean_message = ltrim($clean_message, "'"); // Remove leading apostrophe
              ?>
              <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($clean_message) ?></td>
                  <td><?= $row['created_at'] ?></td>
              </tr>
          <?php endwhile; ?>
      <?php else: ?>
          <tr><td colspan="3" style="text-align:center;color:red;">No notifications found.</td></tr>
      <?php endif; ?>
      </tbody>
  </table>

</div>

<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFont("helvetica", "bold");
    doc.setFontSize(16);
    doc.text("Shantubag Agro Portal – Notifications Report", 14, 20);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(12);
    doc.text("Generated on: <?= date('d/m/Y, h:i:s A'); ?>", 14, 30);

    // FIXED FILTER TEXT (clean ASCII only)
    <?php if ($from_date && $to_date): ?>
    let filterText = "Filter: <?= $from_date ?> → <?= $to_date ?>";
    filterText = filterText.replace(/[^\x20-\x7E]/g, "");
    doc.text(filterText, 14, 40);
    <?php endif; ?>

    doc.autoTable({
        html: "#notificationTable",
        startY: 50,
        headStyles: { fillColor: [27, 94, 32] },
        styles: { fontSize: 10 }
    });

    doc.save("Notifications_Report.pdf");
}
</script>

</body>
</html>
