<?php
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=booking_report.xls");

$conn = new mysqli("localhost", "root", "", "shantubag_db", 3307);

$sql = "SELECT b.booking_id, u.fullname, p.package_name, b.checkin_date, b.checkout_date, b.status 
        FROM bookings b 
        JOIN user u ON b.user_id = u.user_id 
        JOIN packages p ON b.package_id = p.package_id";
$result = $conn->query($sql);

echo "ID\tUser\tPackage\tCheck-in\tCheck-out\tStatus\n";
while($row = $result->fetch_assoc()){
    echo $row['booking_id']."\t".$row['fullname']."\t".$row['package_name']."\t".$row['checkin_date']."\t".$row['checkout_date']."\t".$row['status']."\n";
}
?>
 