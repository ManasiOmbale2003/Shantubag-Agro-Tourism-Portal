<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shantubag_db";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "DELETE FROM user WHERE user_id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: manage_user.php");
    exit();
} else {
    echo "❌ Error: " . $conn->error;
}
?>
