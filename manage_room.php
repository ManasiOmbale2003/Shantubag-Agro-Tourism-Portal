<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shantubag_db";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) die("❌ Connection failed: " . $conn->connect_error);

$msg = "";

// Add Room
if (isset($_POST['save'])) {
    $room_name = trim($_POST['room_name']);
    $price = floatval($_POST['price']);

    $stmt = $conn->prepare("INSERT INTO rooms (room_name, price) VALUES (?, ?)");
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("sd", $room_name, $price);
    $msg = $stmt->execute() ? "✅ Room added successfully!" : "❌ Error: " . $stmt->error;
    $stmt->close();
}

// Delete Room
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id=?");
    if(!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_room.php");
    exit();
}

// Update Room
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $room_name = trim($_POST['room_name']);
    $price = floatval($_POST['price']);

    $stmt = $conn->prepare("UPDATE rooms SET room_name=?, price=? WHERE id=?");
    if(!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("sdi", $room_name, $price, $id);
    $msg = $stmt->execute() ? "✅ Room updated successfully!" : "❌ Error updating room: " . $stmt->error;
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Rooms - Shantubag</title>
<style>
body { font-family: 'Segoe UI', Arial, sans-serif; margin:0; padding:0; background:#f4f7f6; }
header { background: linear-gradient(135deg, #2e7d32, #388e3c); color:white; padding:20px; text-align:center; }
h2 { color: #2e7d32; margin-top:20px; }
.container { width:90%; margin:20px auto; background:white; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
form input, form button { padding:8px 10px; margin:6px 0; border-radius:6px; border:1px solid #ccc; }
form button { background:#2e7d32; color:white; border:none; cursor:pointer; transition:0.3s; }
form button:hover { background:#1b5e20; }
table { border-collapse: collapse; width:100%; margin-top:20px; }
th, td { padding:12px; text-align:center; border-bottom:1px solid #ddd; }
th { background:#388e3c; color:white; }
tr:hover { background:#f1f8f6; }
.btn-del { background:#d32f2f; color:white; padding:6px 12px; border-radius:6px; text-decoration:none; transition:0.3s; }
.btn-del:hover { background:#b71c1c; }
.btn-edit { background:#1976d2; color:white; padding:6px 12px; border:none; border-radius:6px; cursor:pointer; transition:0.3s; }
.btn-edit:hover { background:#0d47a1; }
.msg { margin:10px 0; padding:10px; border-radius:6px; font-weight:bold; }
.success { background:#c8e6c9; color:#256029; }
.error { background:#ffcdd2; color:#b71c1c; }
.pdf-btn { display:inline-block; margin-top:20px; background:#388e3c; color:white; padding:10px 20px; border-radius:6px; font-weight:bold; cursor:pointer; }
.pdf-btn:hover { background:#1b5e20; }
</style>
</head>
<body>

<header>
<h1>🏨 Shantubag - Room Management</h1>
</header>

<div class="container">
    <<a href="AdminDashboard.php" class="back-btn">⬅ Back </a>

<!-- Message -->
<?php if (!empty($msg)) { ?>
<p class="msg <?= (strpos($msg,'✅')!==false)?'success':'error' ?>"><?= $msg ?></p>
<?php } ?>

<!-- Add Room -->
<h2>➕ Add New Room</h2>
<form method="POST">
<input type="text" name="room_name" placeholder="Room Name" required>
<input type="number" step="0.01" name="price" placeholder="Price (₹)" required>
<button type="submit" name="save">Save Room</button>
</form>

<!-- Manage Rooms -->
<h2>📋 Manage Rooms</h2>
<table id="roomTable">
<thead>
<tr>
<th>ID</th>
<th>Room Name</th>
<th>Price (₹)</th>
<th class="no-pdf">Actions</th>
</tr>
</thead>
<tbody>
<?php
$result = $conn->query("SELECT * FROM rooms ORDER BY id DESC");
if(!$result) die("Query failed: " . $conn->error);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()):
?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['room_name']) ?></td>
<td><?= number_format($row['price'],2) ?></td>
<td class="no-pdf">
<form method="POST" style="display:inline-block;">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="text" name="room_name" value="<?= htmlspecialchars($row['room_name']) ?>" required>
<input type="number" step="0.01" name="price" value="<?= $row['price'] ?>" required>
<button type="submit" name="update" class="btn-edit">Update</button>
</form>
<a href="manage_room.php?delete=<?= $row['id'] ?>" class="btn-del" onclick="return confirm('Are you sure to delete this room?')">Delete</a>
</td>
</tr>
<?php endwhile; } else { ?>
<tr><td colspan="4">No rooms available</td></tr>
<?php } ?>
</tbody>
</table>

<button class="pdf-btn" onclick="generatePDF()">📄 Download PDF</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script>
function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(18);
    doc.text("Shantubag Agro Portal Rooms Report", 14, 20);
    doc.setFontSize(12);
    doc.text("Generated on: " + new Date().toLocaleString(), 14, 28);

    // Clone table and remove actions column for PDF
    const table = document.getElementById('roomTable').cloneNode(true);
    const actionCells = table.querySelectorAll('.no-pdf');
    actionCells.forEach(cell => cell.remove());
    doc.autoTable({ html: table, startY: 35, theme: 'striped' });

    doc.save("Shantubag_Rooms_Report.pdf");
}
</script>

</body>
</html>

<?php $conn->close(); ?>
