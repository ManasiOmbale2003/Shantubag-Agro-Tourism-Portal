<?php
$conn = new mysqli("localhost", "root", "", "shantubag_db", 3307);
$id = (int)$_GET['id'];

// Fetch current
$res = $conn->query("SELECT * FROM packages WHERE package_id=$id");
$package = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE packages SET name=?, description=?, price=? WHERE package_id=?");
    $stmt->bind_param("ssdi", $name, $desc, $price, $id);
    if ($stmt->execute()) {
        header("Location: packages_manage.php");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>
<form method="POST">
    <h2>Edit Package</h2>
    <label>Name</label><input type="text" name="name" value="<?= $package['name'] ?>" required><br>
    <label>Description</label><textarea name="description"><?= $package['description'] ?></textarea><br>
    <label>Price</label><input type="number" step="0.01" name="price" value="<?= $package['price'] ?>" required><br>
    <button type="submit">Update</button>
</form>
