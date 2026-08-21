<?php
session_start();
include "db_connect.php";

$table = "contact_messages";

// DELETE RECORD
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM $table WHERE id=?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_contact.php");
    exit();
}

// Filters
$from   = $_GET['from'] ?? "";
$to     = $_GET['to'] ?? "";
$search = $_GET['search'] ?? "";

$where = "1";

// DATE + TIME FILTER
if (!empty($from) && !empty($to)) {
    $from = $conn->real_escape_string($from);
    $to = $conn->real_escape_string($to);
    $where .= " AND created_at BETWEEN '$from' AND '$to'";
}

// Search Filter
if (!empty($search)) {
    $s = $conn->real_escape_string($search);
    $where .= " AND (name LIKE '%$s%' OR message LIKE '%$s%')";
}

$sql = "SELECT id, name, message, created_at FROM $table WHERE $where ORDER BY created_at DESC";
$result = $conn->query($sql);
$rows = [];
while ($r = $result->fetch_assoc()) {
    $rows[] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Manage Messages | Shantubag Agro</title>

<!-- PDF Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<style>
body { font-family: Poppins; background: #f8f2fa; margin: 0; padding: 20px; }

.wrap {
    max-width: 1100px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 10px #d3c2d8;
}

h2 { color:#a1005e; }

table { width: 100%; border-collapse: collapse; margin-top: 15px; }

th {
    background: #a1005e;
    color: #fff;
    padding: 10px;
}

td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.delete-btn {
    background: #d32f2f;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
}

input, button {
  padding: 7px;
  border-radius: 6px;
  border: 1px solid #aaa;
}

.action_hide_pdf { display: table-cell; }
</style>
</head>
<body>

<div class="wrap">

<!-- HEADER -->
<div style="text-align:center;">
    <h1 style="margin:0;font-size:26px;color:#a1005e;">SHANTUBAG AGRO PORTAL</h1>
    <p style="margin:0;">At Post: Kedambe, Tal: Jaoli, Dist: Satara 415012</p>
    <p style="margin:0;">Call: +91 9309906110 / +91 9860549846</p>
    <p style="margin:0;">Email: shantubaug@gmail.com</p>
</div>

<hr>

<h2>📩 Manage Contact Messages</h2>

<!-- FILTER FORM -->
<form method="GET" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:15px">

    <label>From:</label>
    <input type="datetime-local" name="from" value="<?= $from ?>">

    <label>To:</label>
    <input type="datetime-local" name="to" value="<?= $to ?>">

    <input type="text" name="search" placeholder="Search..." value="<?= $search ?>">

    <button type="submit">Filter</button>

    <a href="manage_contact.php" style="padding:7px 14px;background:#ccc;border-radius:6px;text-decoration:none;">
        Reset
    </a>

    <button type="button" onclick="window.print()">Print</button>

    <button type="button" id="downloadPdf">Download PDF</button>

</form>

<?php if (count($rows) == 0): ?>
    <p>No messages found.</p>
<?php else: ?>

<table id="messagesTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Message</th>
            <th>Date & Time</th>
            <th class="action_hide_pdf">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= nl2br(htmlspecialchars($r['message'])) ?></td>
            <td><?= $r['created_at'] ?></td>
            <td class="action_hide_pdf">
                <a class="delete-btn" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this message?')">
                    Delete
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

</div>

<script>
document.getElementById("downloadPdf").onclick = function () {

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');

    // HEADER SECTION
    doc.setFillColor(255, 220, 235);
    doc.rect(0, 0, 600, 60, "F");

    doc.setFontSize(18);
    doc.setTextColor(120, 0, 60);
    doc.text("SHANTUBAG AGRO PORTAL - Messages Report", 300, 25, { align: "center" });

    doc.setFontSize(10);
    doc.text("Contact: +91 9309906110 / +91 9860549846 | Email: shantubaug@gmail.com", 300, 45, { align: "center" });

    // AUTO TABLE
    doc.autoTable({
        html: "#messagesTable",
        startY: 80,
        headStyles: { fillColor: [161, 0, 94] },
        styles: { fontSize: 9, cellPadding: 5 },

        // HIDE DELETE COLUMN IN PDF
        didParseCell: function (data) {
            if (data.cell.raw && data.cell.raw.classList.contains("action_hide_pdf")) {
                data.cell.text = "";
            }
        },

        columnStyles: {
            0: { cellWidth: 120 },  // Name
            1: { cellWidth: 240 },  // Message
            2: { cellWidth: 120 }   // Date
        }
    });

    doc.save("Messages_Report.pdf");
};
</script>

</body>
</html>
