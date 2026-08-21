<?php
session_start();

// ✅ Database connection
$conn = new mysqli("localhost", "root", "", "shantubag_db", 3307);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ❌ REMOVE md5 since your DB stores plain text password
    $stmt = $conn->prepare("SELECT id, username FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ✅ Store session data
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['username'];

        header("Location: AdminDashboard.php");
        exit();
    } else {
        $error = "❌ Invalid Username or Password";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Shantubag Agro Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #a8edea, #fed6e3);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-box input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #aaa;
            border-radius: 8px;
            outline: none;
        }
        .login-box button {
            background: #4888a5ff;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-box button:hover {
            background: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>
