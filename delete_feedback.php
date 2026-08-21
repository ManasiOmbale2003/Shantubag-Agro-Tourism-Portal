<?php
include("db_connect.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM feedback WHERE feedback_id = $id";
    if ($conn->query($sql)) {
        header("Location: manage_feedback.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}
?>
