<?php
$conn = new mysqli("localhost", "root", "", "shantubag_db", 3307);
if ($conn->connect_error) { die("❌ Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO packages (name, description, price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $desc, $price);
    if ($stmt->execute()) {
        header("Location: packages_manage.php");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>
<form method="POST">
    <h2>Add Package</h2>
    <label>Name</label><input type="text" name="name" required><br>
    <label>Description</label><textarea name="description" required></textarea><br>
    <label>Price</label><input type="number" step="0.01" name="price" required><br>
    <button type="submit">Save</button>
</form>
