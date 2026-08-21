<?php
include("db_connect.php");

if (!isset($_GET['id'])) {
    die("❌ Invalid Request");
}

$id = $_GET['id'];

// Fetch package details
$sql = "SELECT * FROM packages WHERE package_id=$id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("❌ Package not found");
}
$package = $result->fetch_assoc();

// Handle Update
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    $update = "UPDATE packages SET 
                name='$name', description='$description', price='$price', duration='$duration' 
               WHERE package_id=$id";

    if ($conn->query($update) === TRUE) {
        echo "<script>alert('✅ Package Updated Successfully');window.location='manage_packages.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Package</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Package</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Package Name</label>
            <input type="text" name="name" value="<?php echo $package['name']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <input type="text" name="description" value="<?php echo $package['description']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo $package['price']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Duration (days)</label>
            <input type="number" name="duration" value="<?php echo $package['duration']; ?>" class="form-control" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="manage_packages.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
