<?php
require(__DIR__ . "/fpdf/fpdf.php");
include "db_connect.php";

class PDF extends FPDF {
    function Header() {
        // Background color
        $this->SetFillColor(255, 230, 235);
        $this->Rect(0, 0, 210, 297, "F");

        // Logo (if needed)
        if (file_exists("logo.png")) {
            $this->Image("logo.png", 10, 8, 20);
        }

        // Title
        $this->SetFont("Arial", "B", 18);
        $this->Cell(0, 10, "SHANTUBAG AGRO TOURISM", 0, 1, "C");

        // Address
        $this->SetFont("Arial", "", 11);
        $this->Cell(0, 6, "At Post: Keldhar, Tal: Jaoli, Dist: Satara - 415012", 0, 1, "C");
        $this->Cell(0, 6, "Call: +91 8600000000 / +91 7500000000", 0, 1, "C");
        $this->Cell(0, 6, "Email: shantubagofficial@gmail.com", 0, 1, "C");

        // Line
        $this->Ln(2);
        $this->SetDrawColor(0,0,0);
        $this->Line(10, 38, 200, 38);

        // Report Title
        $this->Ln(5);
        $this->SetFont("Arial", "B", 14);
        $this->Cell(0, 8, "Booking Report", 0, 1, "C");

        // Date
        date_default_timezone_set("Asia/Kolkata");
        $this->SetFont("Arial", "", 10);
        $this->Cell(0, 6, "Generated On: " . date("d/m/Y h:i A"), 0, 1, "L");

        $this->Ln(4);
    }

    function Footer() {
        // Footer text
        $this->SetY(-15);
        $this->SetFont("Arial", "I", 8);
        $this->Cell(0, 10, "Shantubag Agro Tourism © All Rights Reserved", 0, 0, "C");
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AddPage();

// Table header
$pdf->SetFont("Arial", "B", 11);
$pdf->SetFillColor(255, 160, 175); // pink
$pdf->Cell(10, 10, "ID", 1, 0, "C", true);
$pdf->Cell(40, 10, "Name", 1, 0, "C", true);
$pdf->Cell(40, 10, "Email", 1, 0, "C", true);
$pdf->Cell(28, 10, "Type", 1, 0, "C", true);
$pdf->Cell(15, 10, "Guests", 1, 0, "C", true);
$pdf->Cell(25, 10, "Check-in", 1, 0, "C", true);
$pdf->Cell(25, 10, "Check-out", 1, 0, "C", true);
$pdf->Cell(20, 10, "Total", 1, 1, "C", true);

// Fetch data
$data = $conn->query("SELECT * FROM booking ORDER BY id DESC");

$pdf->SetFont("Arial", "", 10);

while ($row = $data->fetch_assoc()) {

    $pdf->Cell(10, 9, $row['id'], 1);
    $pdf->Cell(40, 9, $row['name'], 1);
    $pdf->Cell(40, 9, $row['email'], 1);
    $pdf->Cell(28, 9, $row['booking_type'], 1);
    $pdf->Cell(15, 9, $row['guests'], 1);
    $pdf->Cell(25, 9, $row['checkin'], 1);
    $pdf->Cell(25, 9, $row['checkout'], 1);
    $pdf->Cell(20, 9, "₹" . $row['total_price'], 1, 1);
}

$pdf->Output();
