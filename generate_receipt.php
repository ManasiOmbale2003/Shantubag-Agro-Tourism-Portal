<?php
require('fpdf/fpdf.php'); // ensure fpdf library exists in /fpdf/ folder
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $booking_type = $_POST['booking_type'];
    $mode = $_POST['payment_mode'];
    $today = date("Y-m-d H:i:s");

    $fileName = "Receipt_" . time() . ".pdf";
    $filePath = "uploads/" . $fileName;

    if (!is_dir("uploads")) mkdir("uploads", 0777, true);

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(0,10,'Shantubag Agro Tourism',0,1,'C');
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,'Booking Payment Receipt',0,1,'C');
    $pdf->Ln(10);

    $pdf->Cell(0,10,"Name: $name",0,1);
    $pdf->Cell(0,10,"Booking Type: $booking_type",0,1);
    $pdf->Cell(0,10,"Payment Mode: $mode",0,1);
    $pdf->Cell(0,10,"Amount Paid: Rs. $amount",0,1);
    $pdf->Cell(0,10,"Date: $today",0,1);
    $pdf->Ln(10);
    $pdf->Cell(0,10,'Thank you for booking with us!',0,1,'C');

    $pdf->Output('F', $filePath);
    echo $filePath;
}
?>
