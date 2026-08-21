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

// DELETE Feedback
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn->query("DELETE FROM feedback WHERE id=$id")) {
        $success = "🗑 Feedback deleted successfully!";
    } else {
        $error = "⚠️ Error deleting feedback!";
    }
}

// FETCH Feedbacks
$result = $conn->query("SELECT * FROM feedback ORDER BY id DESC");
if (!$result) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Feedback | Shantubag Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to bottom right, #f4f6f9, #e8f5e9);
        margin: 0;
        padding: 20px;
    }

    h2 {
        color: #1E8449;
        text-align: center;
        margin-bottom: 20px;
    }

    .back-btn {
        background: #1E8449;
        color: #fff;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 20px;
        font-weight: 500;
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

    .top-buttons {
        text-align: right;
        margin-bottom: 15px;
    }

    .top-buttons button {
        background: #1E8449;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 14px;
        margin-left: 10px;
        cursor: pointer;
        transition: 0.2s;
    }
    .top-buttons button:hover { background: #145A32; }

    .date-filter {
        text-align: right;
        margin-bottom: 10px;
    }

    .date-filter input {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    table {
        width: 100%;
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

    tr:hover {
        background-color: #f2f2f2;
    }

    a.delete {
        background: #E74C3C;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
    }
    a.delete:hover { background: #c0392b; }

    .no-data {
        text-align: center;
        padding: 20px;
        color: gray;
    }
</style>
</head>
<body>

<a href="AdminDashboard.php" class="back-btn">⬅ Back to Dashboard</a>
<h2>💬 Manage Feedback</h2>

<?php if ($success) echo "<div class='msg success'>$success</div>"; ?>
<?php if ($error) echo "<div class='msg error'>$error</div>"; ?>

<div class="date-filter">
    <label for="filterDate">Filter by Date: </label>
    <input type="date" id="filterDate" onchange="filterTable()">
</div>

<div class="top-buttons">
    <button onclick="window.print()">🖨 Print</button>
    <button onclick="downloadPDF()">📄 Download PDF</button>
</div>

<table id="feedbackTable">
    <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['message']) . "</td>
                    <td>{$row['created_at']}</td>
                    <td><a class='delete' href='manage_feedback.php?delete={$row['id']}' onclick='return confirm(\"Delete this feedback?\")'>🗑 Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='no-data'>No feedback found</td></tr>";
    }
    $conn->close();
    ?>
</table>

<script>
function filterTable() {
    const filterDate = document.getElementById('filterDate').value;
    const rows = document.querySelectorAll('#feedbackTable tr');
    rows.forEach((row, index) => {
        if (index === 0) return;
        const dateCell = row.cells[4]?.innerText;
        if (filterDate === "" || (dateCell && dateCell.includes(filterDate))) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape' });

    doc.setFontSize(18);
    doc.text("Shantubag Agro Portal - Feedback Report", 14, 20);
    doc.setFontSize(12);
    const now = new Date();
    doc.text("Generated on: " + now.toLocaleString(), 14, 30);

    const table = document.getElementById("feedbackTable");
    let y = 40;
    for (let i = 0; i < table.rows.length; i++) {
        let row = table.rows[i];
        let rowText = [];
        for (let j = 0; j < row.cells.length - 1; j++) { // exclude last column
            rowText.push(row.cells[j].innerText);
        }
        doc.text(rowText.join(" | "), 14, y);
        y += 8;
        if (y > 190) {
            doc.addPage();
            y = 20;
        }
    }

    const filename = "Feedback_Report_" + now.toISOString().slice(0,19).replace(/[-:T]/g, "") + ".pdf";
    doc.save(filename);
}
</script>

</body>
</html>
