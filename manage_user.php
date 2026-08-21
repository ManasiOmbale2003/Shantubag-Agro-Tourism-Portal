<?php
session_start();
include("db_connect.php");

$success = "";
$error = "";

// ✅ Handle Delete User
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success = "🗑 User deleted successfully!";
        } else {
            $error = "❌ Error deleting user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "❌ SQL Error: " . $conn->error;
    }
}

// ✅ Date Filter
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$query = "SELECT * FROM users";
if ($filter_date) {
    $query .= " WHERE DATE(created_at) = '$filter_date'";
}
$query .= " ORDER BY id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users - Shantubag Agro Portal</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ============================
   REFUND REPORT COLOR THEME
   ============================ */
body {
    background: #f8f7f5ff;
    font-family: 'Poppins', sans-serif;
}
header {
    background: #8b0033;
    color: white;
    padding: 20px;
    text-align: center;
    border-bottom: 4px solid #6a0028;
}
h1 { margin: 0; font-size: 50px; font-weight: 600; }
.container {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.15);
    padding: 30px;
    margin-top: 40px;
}
.back-btn {
    background: #8b0033;
    color: white;
    border-radius: 8px;
    padding: 8px 15px;
    text-decoration: none;
}
.back-btn:hover {
    background: #d7bec8ff;
    color: #fff;
}
.table thead {
    background: #d9a8a8;
    color: black;
    border: 1px solid #8b0033;
}
.table tbody tr td {
    background: #f3dede;
    border: 1px solid #8b0033;
    color: black;
}
footer {
    background: #8b0033;
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: 50px;
    border-top: 4px solid #df84a7ff;
}
.btn-print, .btn-pdf {
    background-color: #d05481ff;
    color: white;
    border: none;
}
.btn-print:hover, .btn-pdf:hover {
    background-color: #6a0028;
}
</style>
</head>
<body>

<header>
    <h1>👥 Manage Users</h1>
    <h2>SHANTUBAG AGRO PORTAL
        At Post: Kedambe, Medha Bondarwadi Road, Jaoli, Maharashtra 415012
    </h2>
</header>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="AdminDashboard.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>

    <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <!-- Filter -->
    <div class="filter-section d-flex justify-content-between align-items-center mb-3">
        <form method="GET" class="d-flex gap-2">
            <input type="date" name="filter_date" class="form-control" value="<?= htmlspecialchars($filter_date) ?>">
            <button type="submit" class="btn btn-success">Filter</button>
            <a href="manage_user.php" class="btn btn-secondary">Reset</a>
        </form>

        <div>
            <button onclick="generatePDF()" class="btn btn-pdf">📄 Download PDF</button>
            <button onclick="window.print()" class="btn btn-print">🖨 Print</button>
        </div>
    </div>

    <!-- Users -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['fullname']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><?= $row['created_at']; ?></td>
                        <td>
                            <a class="btn btn-danger btn-sm"
                               href="manage_user.php?delete=<?= $row['id']; ?>"
                               onclick="return confirm('Are you sure you want to delete this user?')">🗑 Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-danger">❌ No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <p>&copy; <?= date("Y"); ?> Shantubag Agro Portal | Admin Panel</p>
</footer>

<!-- JS PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
function generatePDF() {
    // Hide Action Column
    let actionIndex = 5; // 6th column (0-based index)
    document.querySelectorAll("#userTable tr").forEach(row => {
        if (row.children[actionIndex]) {
            row.children[actionIndex].style.display = "none";
        }
    });

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF("p", "pt", "a4");

    /* ==========================
       CENTERED HEADER
       ========================== */

    doc.setFont("helvetica", "bold");
    doc.setFontSize(20);
    doc.setTextColor(139, 0, 51);
    doc.text("SHANTUBAG AGRO PORTAL", 300, 40, { align: "center" });

    doc.setFont("helvetica", "normal");
    doc.setFontSize(11);
    doc.setTextColor(0, 0, 0);
    doc.text("At Post: Kedambe, Medha Bondarwadi Road, Jaoli, Maharashtra 415012", 300, 60, { align: "center" });
    doc.text("Contact: +91 9309906110 | +91 9860549846", 300, 75, { align: "center" });
    doc.text("Email: shantubag@gmail.com", 300, 90, { align: "center" });

    doc.setDrawColor(139, 0, 51);
    doc.line(40, 100, 555, 100);

    doc.setFontSize(16);
    doc.setTextColor(139, 0, 51);
    doc.text("User Report", 300, 125, { align: "center" });

    const now = new Date();
    doc.setFontSize(10);
    doc.setTextColor(0, 0, 0);
    doc.text("Generated on: " + now.toLocaleDateString() + " " + now.toLocaleTimeString(), 40, 140);

    /* ==========================
       PDF TABLE
       ========================== */
    doc.autoTable({
        html: "#userTable",
        startY: 160,
        headStyles: { fillColor: [217, 168, 168], textColor: 0, halign: "center" },
        styles: { fontSize: 10, cellPadding: 5 },
        bodyStyles: { fillColor: [243, 222, 222] },
        tableLineColor: [139, 0, 51],
        tableLineWidth: 0.5,
        columnStyles: {
            5: { cellWidth: 0 } // hide "Action" column completely
        }
    });

    const filename = "User_Report_" + now.toLocaleDateString().replace(/\//g, "-") + ".pdf";
    doc.save(filename);

    // SHOW ACTION COLUMN AGAIN
    document.querySelectorAll("#userTable tr").forEach(row => {
        if (row.children[actionIndex]) {
            row.children[actionIndex].style.display = "";
        }
    });
}
</script>


</body>
</html>
