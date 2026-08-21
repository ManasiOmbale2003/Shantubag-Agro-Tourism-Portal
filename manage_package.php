<?php
include("db_connect.php");

$success = "";
$error = "";

// ✅ Handle Add Package
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    $stmt = $conn->prepare("INSERT INTO packages (name, description, price) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $name, $description, $price);
        if ($stmt->execute()) {
            $success = "✅ Package Added Successfully!";
        } else {
            $error = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "❌ SQL Error: " . $conn->error;
    }
}

// Fetch Packages
$result = $conn->query("SELECT * FROM packages ORDER BY id DESC");
if (!$result) die("❌ Query failed: " . $conn->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Packages - Shantubag Agro Portal</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background: #f3f9f4; font-family: 'Poppins', sans-serif; }
header { background: linear-gradient(90deg, #27ae60, #2ecc71); color: #fff; padding: 30px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
h1 { margin:0; font-size:30px; }
.card { border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
.btn-success { background:#27ae60; border:none; }
.btn-success:hover { background:#1e8449; }
footer { background:#2c3e50; color:white; text-align:center; padding:15px; margin-top:30px; border-top-left-radius:12px; border-top-right-radius:12px; }
.table-hover tbody tr:hover { background-color: #eafbea; }
.back-btn { display:inline-block; margin:20px; text-decoration:none; color:#fff; background:#3498db; padding:8px 15px; border-radius:6px; transition:0.3s; }
.back-btn:hover { background:#2c80b4; }
.report-controls { display:flex; justify-content:flex-end; align-items:center; gap:10px; margin-bottom:15px; }
input[type=date] { padding:6px; border-radius:4px; border:1px solid #ccc; }
</style>
</head>

<body>

<header>
    <h1>📦 Manage Packages</h1>
</header>

<a href="AdminDashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<div class="container my-4">

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card p-4 mb-4">
        <h4 class="mb-3">➕ Add New Package</h4>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Package Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="price" class="form-control" placeholder="Price (₹ or text)" required>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" name="add" class="btn btn-success">Add</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Report Controls -->
    <div class="report-controls">
        <input type="date" id="fromDate">
        <span>to</span>
        <input type="date" id="toDate">
        <button class="btn btn-outline-primary btn-sm" onclick="generateDatewisePDF()">📅 Date-wise PDF</button>
        <button class="btn btn-outline-success btn-sm" onclick="generatePDF()">📄 Download PDF</button>
        <button class="btn btn-outline-dark btn-sm" onclick="window.print()">🖨 Print</button>
    </div>

    <div class="card p-3">
        <table class="table table-bordered table-striped table-hover" id="packageTable">
            <thead class="table-success text-center">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th class="no-export">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['price']) ?></td>
                            <td class="no-export">
                                <a href="edit_package.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏ Edit</a>
                                <a href="delete_package.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">🗑 Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-danger">No Packages Found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <p>&copy; <?= date("Y"); ?> Shantubag Agro Portal | Admin Panel</p>
</footer>

<!-- jsPDF for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(18);
    doc.text("Shantubag Agro Portal", 14, 15);
    doc.setFontSize(12);
    doc.text("All Packages Report", 14, 25);
    doc.autoTable({ html: "#packageTable", startY: 40, theme: "striped", 
        didParseCell: function (data) {
            if (data.column.index === 4) { data.cell.hidden = true; } // hide Action column
        }
    });
    doc.save("Packages_Report.pdf");
}

function generateDatewisePDF() {
    const fromDate = document.getElementById("fromDate").value;
    const toDate = document.getElementById("toDate").value;
    if (!fromDate || !toDate) { alert("Please select From and To dates"); return; }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(18);
    doc.text("Shantubag Agro Portal", 14, 15);
    doc.setFontSize(12);
    doc.text(`Packages Report (${fromDate} to ${toDate})`, 14, 25);

    const headers = [];
    const rows = [];
    const table = document.querySelector("#packageTable");

    table.querySelectorAll("thead tr th").forEach((th, i) => {
        if (!th.classList.contains("no-export")) headers.push(th.innerText);
    });

    table.querySelectorAll("tbody tr").forEach(tr => {
        const row = [];
        tr.querySelectorAll("td").forEach((td, i) => {
            if (!table.querySelectorAll("thead tr th")[i].classList.contains("no-export")) {
                row.push(td.innerText);
            }
        });
        rows.push(row);
    });

    doc.autoTable({ startY: 40, head: [headers], body: rows, theme: "striped" });
    doc.save(`Packages_Report_${fromDate}_to_${toDate}.pdf`);
}
</script>
</body>
</html>
