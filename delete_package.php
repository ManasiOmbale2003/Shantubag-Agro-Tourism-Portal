<?php
include("db_connect.php");

if (!isset($_GET['id'])) {
    die("❌ Invalid Request");
}

$id = $_GET['id'];

$sql = "DELETE FROM packages WHERE package_id=$id";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('🗑 Package Deleted Successfully');window.location='manage_packages.php';</script>";
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
