<?php
session_start();
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 🧹 Sanitize input
    $fullname   = trim($_POST['fullname']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $password   = $_POST['password'];
    $cpassword  = $_POST['cpassword'];

    // 🔍 Server-side validation
    if (strlen($fullname) < 3) {
        $_SESSION['error'] = "⚠️ Full name must be at least 3 characters.";
        header("Location: user_register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "⚠️ Invalid email address.";
        header("Location: user_register.php");
        exit();
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $_SESSION['error'] = "⚠️ Phone number must be 10 digits.";
        header("Location: user_register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "⚠️ Password must be at least 6 characters long.";
        header("Location: user_register.php");
        exit();
    }

    if ($password !== $cpassword) {
        $_SESSION['error'] = "❌ Passwords do not match!";
        header("Location: user_register.php");
        exit();
    }

    // 🧠 Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: user_register.php");
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "⚠️ Email already registered!";
        $stmt->close();
        header("Location: user_register.php");
        exit();
    }
    $stmt->close();

    // 🔒 Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // 🧾 Insert into database
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: user_register.php");
        exit();
    }

    $stmt->bind_param("ssss", $fullname, $email, $phone, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Registration successful! Please login.";
        header("Location: user_login.php");
    } else {
        $_SESSION['error'] = "❌ Something went wrong. Please try again.";
        header("Location: user_register.php");
    }

    $stmt->close();
}
$conn->close();
?>
