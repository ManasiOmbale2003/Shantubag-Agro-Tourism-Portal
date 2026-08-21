<?php
include "db_connect.php";

if (isset($_GET['type']) && isset($_GET['value']) && isset($_GET['checkin']) && isset($_GET['checkout'])) {
    $type = $_GET['type'];
    $value = $_GET['value'];
    $checkin = $_GET['checkin'];
    $checkout = $_GET['checkout'];

    if ($type === "Package") {
        $query = $conn->prepare("SELECT capacity FROM packages WHERE package_name = ?");
    } else {
        $query = $conn->prepare("SELECT capacity FROM rooms WHERE room_type = ?");
    }

    $query->bind_param("s", $value);
    $query->execute();
    $res = $query->get_result();
    $info = $res->fetch_assoc();
    $capacity = $info['capacity'] ?? 0;

    $sql = "SELECT COALESCE(SUM(guests),0) AS total_booked
            FROM bookings
            WHERE booking_type = ? 
            AND ((checkin <= ? AND checkout >= ?) OR (checkin <= ? AND checkout >= ?))
            AND status='Confirmed'";

    if ($type === "Package") $sql .= " AND package_type = ?";
    else $sql .= " AND room_type = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $type, $checkin, $checkin, $checkout, $checkout, $value);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $booked = $result['total_booked'] ?? 0;

    if ($booked >= $capacity) echo "full";
    else echo "available";
}
?>
