<?php
// DB Connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "shantubag_db";
$port       = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Fetch visitors
$sql = "SELECT id, name, email, visit_date FROM visitors ORDER BY visit_date DESC";
$result = $conn->query($sql);

$visitors = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $visitors[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($visitors);
$conn->close();
?>
