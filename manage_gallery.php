<?php
// DB Connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "shantubag_db"; 
$port       = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$success = "";
$error   = "";

// ✅ ADD Gallery Image
if (isset($_POST['add'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    if (!empty($image)) {
        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_ext)) {
            $uniqueName = time() . "_" . basename($image);
            $target = "../uploads/" . $uniqueName;

            if (move_uploaded_file($tmp_name, $target)) {
                $stmt = $conn->prepare("INSERT INTO gallery (title, image) VALUES (?, ?)");
                $stmt->bind_param("ss", $title, $uniqueName);
                if ($stmt->execute()) {
                    $success = "✅ Image uploaded successfully!";
                } else {
                    $error = "❌ Database error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "❌ Failed to move uploaded file.";
            }
        } else {
            $error = "❌ Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.";
        }
    } else {
        $error = "❌ Please select an image.";
    }
}

// ✅ DELETE Image
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT image FROM gallery WHERE gallery_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $row = $res->fetch_assoc()) {
        $filePath = "../uploads/" . $row['image'];
        if (file_exists($filePath)) {
            unlink($filePath); // delete image from folder
        }
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM gallery WHERE gallery_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "🗑 Image deleted successfully!";
    }
    $stmt->close();
}

// ✅ FETCH Images with error handling
$result = $conn->query("SELECT * FROM gallery ORDER BY gallery_id DESC");
if (!$result) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Gallery - Shantubag</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background: #f4f9f4; }
    header { background: #6f42c1; color: white; padding: 20px; text-align: center; }
    img.thumb { width: 120px; height: 90px; object-fit: cover; border-radius: 6px; }
</style>
</head>
<body>

<header><h1>🖼 Manage Gallery</h1></header>
<div class="container mt-4">
<<a href="AdminDashboard.php" class="back-btn">⬅ Back</a>
  <!-- ✅ Messages -->
  <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
  <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

  <!-- Add Image Form -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-light"><b>Add New Image</b></div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <input type="text" name="title" class="form-control" placeholder="Image Title" required>
        </div>
        <div class="mb-3">
          <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" name="add" class="btn btn-success">➕ Add Image</button>
      </form>
    </div>
  </div>

  <!-- PDF Button -->
  <button class="btn btn-primary mb-3" onclick="generatePDF()">📄 Download PDF</button>

  <!-- Gallery Table -->
  <div class="table-responsive">
    <table class="table table-bordered table-striped" id="galleryTable">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Image</th>
          <th class="no-export">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['gallery_id']; ?></td>
              <td><?= htmlspecialchars($row['title']); ?></td>
              <td><img class="thumb" src="../uploads/<?= htmlspecialchars($row['image']); ?>"></td>
              <td>
                <a class="btn btn-danger btn-sm" href="manage_gallery.php?delete=<?= $row['gallery_id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this image?')">🗑 Delete</a>
              </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center text-danger">❌ No images found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>
<footer class="bg-dark text-white text-center p-3 mt-4">
  &copy; <?= date("Y"); ?> Shantubag Agro Portal. Admin Panel
</footer>

<!-- ✅ jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script>
function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(18).text("Shantubag Gallery Report", 14, 15);
    doc.setFontSize(11).text("Generated on: " + new Date().toLocaleString(), 14, 25);

    doc.autoTable({
        html: "#galleryTable",
        startY: 35,
        theme: "striped",
        columnStyles: { 2: { cellWidth: 40 } },
        didParseCell: function (data) {
            // Hide image and action column in PDF
            if (data.column.index === 2 || data.column.index === 3) {
                data.cell.hidden = true;
            }
        }
    });
    doc.save("Gallery_Report.pdf");
}
</script>

</body>
</html>

<?php $conn->close(); ?>
