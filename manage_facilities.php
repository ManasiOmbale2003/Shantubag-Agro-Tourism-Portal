<?php
// DB Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shantubag_db";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

// ADD Facility
if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);

    if (!empty($name) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO facilities (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute()) {
            $success = "✅ Facility added successfully!";
        } else {
            $error = "❌ Database error: " . $stmt->error;
        }
    } else {
        $error = "⚠️ Please fill in all fields.";
    }
}

// DELETE Facility
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn->query("DELETE FROM facilities WHERE facility_id=$id")) {
        $success = "🗑 Facility deleted successfully!";
    } else {
        $error = "❌ Delete error: " . $conn->error;
    }
}

// FETCH Facilities
$sql = "SELECT * FROM facilities ORDER BY facility_id DESC";
$result = $conn->query($sql);
if (!$result) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🌿 Manage Facilities | Shantubag Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to bottom right, #f4f6f9, #e8f5e9);
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #1E8449;
        margin-bottom: 20px;
    }

    .back-btn {
        background: #1E8449;
        color: white;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        display: inline-block;
        margin-bottom: 15px;
    }
    .back-btn:hover { background: #145A32; }

    .msg {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 6px;
        width: 60%;
        margin: 10px auto;
        text-align: center;
        font-weight: 500;
    }
    .success { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }

    form {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
        margin-bottom: 25px;
        width: 90%;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    input[type=text], textarea {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 15px;
    }

    button {
        background: #1E8449;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
    }
    button:hover { background: #145A32; }

    .pdf-controls {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: space-between;
        align-items: center;
        margin: 20px auto;
        width: 90%;
        max-width: 900px;
    }

    .pdf-btn {
        background: #1E8449;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 14px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.3s;
    }
    .pdf-btn:hover { background: #145A32; }

    input[type="date"] {
        padding: 6px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    table {
        width: 90%;
        margin: auto;
        border-collapse: collapse;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
    }

    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #1E8449;
        color: white;
    }

    tr:hover { background-color: #f2f2f2; }

    .delete-btn {
        background: #E74C3C;
        color: white;
        padding: 6px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    .delete-btn:hover { background: #c0392b; }

    @media print {
        .pdf-controls, form, .back-btn { display: none; }
        body { background: white; }
    }
</style>
</head>
<body>

<a href="AdminDashboard.php" class="back-btn">⬅ Back to Dashboard</a>
<h2>🏡 Manage Facilities</h2>

<?php if($success) echo "<div class='msg success'>$success</div>"; ?>
<?php if($error) echo "<div class='msg error'>$error</div>"; ?>

<!-- Add New Facility Form -->
<form method="POST">
    <input type="text" name="name" placeholder="Facility Name" required>
    <textarea name="description" placeholder="Facility Description" required></textarea>
    <button type="submit" name="add"><i class="fa fa-plus"></i> Add Facility</button>
</form>

<!-- PDF Controls -->
<div class="pdf-controls">
    <div>
        <button class="pdf-btn" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
        <button class="pdf-btn" onclick="generatePDF()"><i class="fa fa-download"></i> Download PDF</button>
    </div>
    <div>
        <label>From:</label>
        <input type="date" id="fromDate">
        <label>To:</label>
        <input type="date" id="toDate">
        <button class="pdf-btn" onclick="generateDatewisePDF()"><i class="fa fa-calendar"></i> Date-wise PDF</button>
    </div>
</div>

<!-- Facilities Table -->
<table id="facilityTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Facility Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['facility_id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['description']); ?></td>
                    <td>
                        <a href="?delete=<?= $row['facility_id']; ?>" onclick="return confirm('Are you sure you want to delete this facility?');">
                            <button class="delete-btn"><i class="fa fa-trash"></i> Delete</button>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No facilities found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
function generatePDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  doc.setFontSize(18);
  doc.text("Shantubag Agro Portal", 14, 15);
  doc.setFontSize(14);
  doc.text("Facilities Report", 14, 25);
  doc.setFontSize(10);
  doc.text("Generated: " + new Date().toLocaleString(), 14, 32);

  const headers = [];
  const rows = [];
  const table = document.getElementById("facilityTable");

  table.querySelectorAll("thead th").forEach((th, index, arr) => {
    if (index < arr.length - 1) headers.push(th.innerText);
  });

  table.querySelectorAll("tbody tr").forEach(tr => {
    const row = [];
    tr.querySelectorAll("td").forEach((td, i, arr) => {
      if (i < arr.length - 1) row.push(td.innerText);
    });
    rows.push(row);
  });

  doc.autoTable({ startY: 40, head: [headers], body: rows, theme: "striped" });
  doc.save("Facilities_Report.pdf");
}

function generateDatewisePDF() {
  const from = document.getElementById("fromDate").value;
  const to = document.getElementById("toDate").value;
  if (!from || !to) return alert("Please select both From and To dates.");

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  doc.setFontSize(18);
  doc.text("Shantubag Agro Portal", 14, 15);
  doc.setFontSize(14);
  doc.text("Facilities Report (Date-wise)", 14, 25);
  doc.setFontSize(10);
  doc.text(`Generated: ${new Date().toLocaleString()} | From: ${from} To: ${to}`, 14, 32);

  const headers = [];
  const rows = [];
  const table = document.getElementById("facilityTable");

  table.querySelectorAll("thead th").forEach((th, index, arr) => {
    if (index < arr.length - 1) headers.push(th.innerText);
  });

  table.querySelectorAll("tbody tr").forEach(tr => {
    const row = [];
    tr.querySelectorAll("td").forEach((td, i, arr) => {
      if (i < arr.length - 1) row.push(td.innerText);
    });
    rows.push(row);
  });

  doc.autoTable({ startY: 40, head: [headers], body: rows, theme: "striped" });
  doc.save(`Facilities_Report_${from}_to_${to}.pdf`);
}
</script>

</body>
</html>
