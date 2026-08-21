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
$sql = "SELECT * FROM user WHERE user_id=$id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    $sql = "UPDATE user SET fullname='$fullname', email='$email', password='$password', phone='$phone' WHERE user_id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_user.php");
        exit();
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; }
        form {
            width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 { text-align:center; color:#2e8b57; }
        label { display:block; margin:10px 0 5px; }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        button {
            background:#2e8b57;
            color:white;
            padding:10px;
            border:none;
            width:100%;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2>Edit User</h2>
<form method="POST">
    <label>Fullname</label>
    <input type="text" name="fullname" value="<?= $user['fullname'] ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= $user['email'] ?>" required>

    <label>Password</label>
    <input type="text" name="password" value="<?= $user['password'] ?>" required>

    <label>Phone</label>
    <input type="text" name="phone" value="<?= $user['phone'] ?>" required>

    <button type="submit">Update User</button>
</form>

</body>
</html>
