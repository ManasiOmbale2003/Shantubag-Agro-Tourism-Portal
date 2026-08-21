<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../db_connect.php";

// Fetch data
$users = $conn->query("SELECT id, name, email, phone, created_at FROM users ORDER BY created_at DESC");
$feedback = $conn->query("SELECT f.feedback_id, f.comments, f.submitted_at, u.name AS user_name, u.email
                          FROM feedback f LEFT JOIN users u ON f.id=u.id ORDER BY f.submitted_at DESC");
$payments = $conn->query("SELECT p.payment_id, p.amount, p.receipt, p.created_at, u.name
                          FROM payments p LEFT JOIN users u ON p.id=u.id ORDER BY p.created_at DESC");

// PDF Export
if (isset($_GET['download'])) {
    require("../fpdf/fpdf.php");
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);

    if ($_GET['download'] == 'users') {
        $pdf->Cell(0,10,'Shantubag Agro Portal - User Report',0,1,'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(20,10,'ID',1);
        $pdf->Cell(40,10,'Name',1);
        $pdf->Cell(60,10,'Email',1);
        $pdf->Cell(40,10,'Phone',1);
        $pdf->Cell(30,10,'Joined',1);
        $pdf->Ln();

        $pdf->SetFont('Arial','',10);
        $res = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
        while($row = $res->fetch_assoc()) {
            $pdf->Cell(20,10,$row['id'],1);
            $pdf->Cell(40,10,$row['name'],1);
            $pdf->Cell(60,10,$row['email'],1);
            $pdf->Cell(40,10,$row['phone'],1);
            $pdf->Cell(30,10,$row['created_at'],1);
            $pdf->Ln();
        }
    }

    elseif ($_GET['download'] == 'payments') {
        $pdf->Cell(0,10,'Shantubag Agro Portal - Payments Report',0,1,'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(20,10,'ID',1);
        $pdf->Cell(40,10,'User',1);
        $pdf->Cell(40,10,'Amount',1);
        $pdf->Cell(50,10,'Receipt',1);
        $pdf->Cell(40,10,'Date',1);
        $pdf->Ln();

        $pdf->SetFont('Arial','',10);
        $res = $conn->query("SELECT p.payment_id, p.amount, p.receipt, p.created_at, u.name 
                             FROM payments p LEFT JOIN users u ON p.id=u.id ORDER BY p.created_at DESC");
        while($row = $res->fetch_assoc()) {
            $pdf->Cell(20,10,$row['payment_id'],1);
            $pdf->Cell(40,10,$row['name'],1);
            $pdf->Cell(40,10,$row['amount'],1);
            $pdf->Cell(50,10,$row['receipt'],1);
            $pdf->Cell(40,10,$row['created_at'],1);
            $pdf->Ln();
        }
    }

    elseif ($_GET['download'] == 'feedback') {
        $pdf->Cell(0,10,'Shantubag Agro Portal - Feedback Report',0,1,'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(20,10,'ID',1);
        $pdf->Cell(40,10,'User',1);
        $pdf->Cell(60,10,'Email',1);
        $pdf->Cell(70,10,'Comments',1);
        $pdf->Ln();

        $pdf->SetFont('Arial','',10);
        $res = $conn->query("SELECT f.feedback_id, f.comments, f.submitted_at, u.name AS user_name, u.email
                             FROM feedback f LEFT JOIN users u ON f.user_id=u.id ORDER BY f.submitted_at DESC");
        while($row = $res->fetch_assoc()) {
            $pdf->Cell(20,10,$row['feedback_id'],1);
            $pdf->Cell(40,10,($row['user_name'] ?: 'Guest'),1);
            $pdf->Cell(60,10,$row['email'],1);
            $pdf->Cell(70,10,substr($row['comments'],0,30),1); // shorten long comments
            $pdf->Ln();
        }
    }

    $pdf->Output();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Reports - Admin</title>
<style>
    body { font-family: Arial, sans-serif; background:#f5f7fb; margin:20px; }
    .container { max-width: 1000px; margin: auto; }
    h2 { color: #2AA876; margin-top:30px; }
    table { width: 100%; border-collapse: collapse; margin: 15px 0; background:#fff; border-radius:8px; overflow:hidden; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align:left; }
    th { background:#1976d2; color:#fff; }
    a.btn { display:inline-block; padding:8px 14px; background:#1976d2; color:#fff; text-decoration:none; border-radius:5px; margin:10px 0; }
    a.btn:hover { background:#0d47a1; }
</style>
</head>
<body>
<div class="container">
    <h1>📊 Shantubag Agro Portal - Reports</h1>

    <!-- Users Report -->
    <h2>👥 Users</h2>
    <a class="btn" href="?download=users">⬇ Download PDF</a>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th></tr>
        <?php while($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $u['user_id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone']) ?></td>
            <td><?= $u['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Feedback Report -->
    <h2>💬 Feedback</h2>
    <a class="btn" href="?download=feedback">⬇ Download PDF</a>
    <table>
        <tr><th>ID</th><th>User</th><th>Email</th><th>Comments</th><th>Submitted</th></tr>
        <?php while($f = $feedback->fetch_assoc()): ?>
        <tr>
            <td><?= $f['feedback_id'] ?></td>
            <td><?= htmlspecialchars($f['user_name'] ?: 'Guest') ?></td>
            <td><?= htmlspecialchars($f['email']) ?></td>
            <td><?= htmlspecialchars($f['comments']) ?></td>
            <td><?= $f['submitted_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Payments Report -->
    <h2>💵 Payments</h2>
    <a class="btn" href="?download=payments">⬇ Download PDF</a>
    <table>
        <tr><th>ID</th><th>User</th><th>Amount</th><th>Receipt</th><th>Date</th></tr>
        <?php while($p = $payments->fetch_assoc()): ?>
        <tr>
            <td><?= $p['payment_id'] ?></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= $p['amount'] ?></td>
            <td><a href="../uploads/<?= htmlspecialchars($p['receipt']) ?>" target="_blank">View</a></td>
            <td><?= $p['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
