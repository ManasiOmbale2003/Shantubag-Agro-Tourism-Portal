<?php
require("fpdf/fpdf.php"); // ✅ Make sure FPDF library is in your project folder

$conn = new mysqli("localhost", "root", "", "shantubag", 3307);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$sql = "SELECT * FROM feedback ORDER BY created_at DESC";
$result = $conn->query($sql);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 16);
$pdf->Cell(0, 10, "Shantubag Agro Portal - Feedback Report", 0, 1, "C");
$pdf->Ln(10);

// Table header
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(10, 10, "ID", 1);
$pdf->Cell(40, 10, "Name", 1);
$pdf->Cell(50, 10, "Email", 1);
$pdf->Cell(20, 10, "Rating", 1);
$pdf->Cell(70, 10, "Message", 1);
$pdf->Ln();

// Table rows
$pdf->SetFont("Arial", "", 10);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(10, 10, $row["id"], 1);
    $pdf->Cell(40, 10, $row["name"], 1);
    $pdf->Cell(50, 10, $row["email"], 1);
    $pdf->Cell(20, 10, str_repeat("⭐", $row["rating"]), 1);
    $pdf->Cell(70, 10, substr($row["message"], 0, 40) . "...", 1);
    $pdf->Ln();
}

$pdf->Output();
$conn->close();
?>
