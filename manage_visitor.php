<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit; }
include "db_connect.php";

// Date filter
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$where = "";

if(!empty($from_date) && !empty($to_date)){
    $where = "WHERE visit_date BETWEEN '$from_date' AND '$to_date'";
}

// Fetch visitors data
$sql = "SELECT id, name, email, phone, visit_date FROM visitors $where ORDER BY visit_date DESC";
$res = $conn->query($sql);
if (!$res) {
    die("❌ SQL Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Visitors Report | Shantubag Agro Portal</title>
<style>
body { font-family: 'Poppins', sans-serif; background: #f8f5e0; margin: 40px; color: #2f2f2f; }
.report-container { background: #f5f1de; border-radius: 8px; padding: 30px 40px; }
h2 { font-size: 22px; font-weight: 600; margin-bottom: 5px; }
.generated, .date-range { font-size: 14px; margin-bottom: 10px; }
table { width: 100%; border-collapse: collapse; font-size: 15px; }
th, td { padding: 10px 12px; border-bottom: 1px solid #ddd; text-align: left; }
th { background-color: #4a7c9b; color: #fff; }
tr:nth-child(even) { background-color: #f2f2f0; }
tr:hover { background-color: #e0ebde; }
.btn-container { margin-top: 20px; text-align: center; }
button { background-color: #4a7c9b; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; transition: 0.3s; }
button:hover { background-color: #3b5f73; }
form { margin-bottom: 15px; text-align: center; }
input[type="date"] { padding: 5px 10px; margin: 0 5px; border-radius: 5px; border: 1px solid #ccc; }
</style>
</head>
<body>

<form method="get">
    From: <input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>">
    To: <input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>">
    <button type="submit">Filter</button>
   
</form>

<div class="report-container" id="report-content">
  <h2>Visitors Report <?= (!empty($from_date) && !empty($to_date)) ? "(Date-wise)" : "" ?></h2>
  <div class="generated">Generated on: <?= date('m/d/Y, h:i:s A') ?></div>
  <?php if(!empty($from_date) && !empty($to_date)): ?>
    <div class="date-range">From: <?= $from_date ?> To: <?= $to_date ?></div>
  <?php endif; ?>

  <table id="visitorsTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Visit Date</th>
      </tr>
    </thead>
    <tbody>
      <?php if($res->num_rows > 0): ?>
          <?php while($row = $res->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['visit_date']) ?></td>
          </tr>
          <?php endwhile; ?>
      <?php else: ?>
          <tr><td colspan="5" style="text-align:center; color:red;">No records found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="btn-container">
  <button id="downloadPDF">📄 Download PDF</button>
</div>

<!-- jsPDF + AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script>
document.getElementById("downloadPDF").addEventListener("click", () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const title = "Visitors Report <?= (!empty($from_date) && !empty($to_date)) ? '(Date-wise)' : '' ?>";
    const now = new Date();
    const fromDate = "<?= $from_date ?>";
    const toDate = "<?= $to_date ?>";

    doc.setFontSize(16);
    doc.text("Shantubag Agro Portal", 14, 15);
    doc.setFontSize(12);
    doc.text(title, 14, 25);
    doc.text("Generated on: " + now.toLocaleString(), 14, 32);

    if(fromDate && toDate){
        doc.text(`From: ${fromDate} To: ${toDate}`, 14, 39);
    }

    const tableColumn = ["ID","Name","Email","Phone","Visit Date"];
    const tableRows = [];

    <?php if($res->num_rows > 0): ?>
      <?php foreach($res as $row): ?>
        tableRows.push([
          "<?= $row['id'] ?>",
          "<?= addslashes($row['name']) ?>",
          "<?= addslashes($row['email']) ?>",
          "<?= addslashes($row['phone']) ?>",
          "<?= $row['visit_date'] ?>"
        ]);
      <?php endforeach; ?>
    <?php endif; ?>

    doc.autoTable({
        head: [tableColumn],
        body: tableRows,
        startY: 45,
        theme: 'striped',
        headStyles: { fillColor: [74, 124, 155] },
    });

    let filename = "Visitors_Report";
    if(fromDate && toDate) filename += `_from_${fromDate}_to_${toDate}`;
    filename += `_${now.getFullYear()}${now.getMonth()+1}${now.getDate()}_${now.getHours()}${now.getMinutes()}${now.getSeconds()}.pdf`;
    doc.save(filename);
});
</script>

</body>
</html>
