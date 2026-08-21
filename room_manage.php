<?php
$conn = new mysqli("localhost","root","","shantubag",3307);
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM rooms WHERE room_id=".(int)$_GET['delete']);
    header("Location: rooms_manage.php");
    exit();
}
$res = $conn->query("SELECT * FROM rooms");
?>
<h2>🛏 Manage Rooms</h2>
<a href="room_add.php">➕ Add Room</a>
<table border="1">
<tr><th>ID</th><th>Room Type</th><th>Price</th><th>Status</th><th>Action</th></tr>
<?php while($r=$res->fetch_assoc()): ?>
<tr>
<td><?= $r['room_id'] ?></td>
<td><?= $r['room_type'] ?></td>
<td><?= $r['price'] ?></td>
<td><?= $r['status'] ?></td>
<td>
<a href="room_edit.php?id=<?= $r['room_id'] ?>">✏ Edit</a> | 
<a href="rooms_manage.php?delete=<?= $r['room_id'] ?>" onclick="return confirm('Delete room?')">🗑 Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>
