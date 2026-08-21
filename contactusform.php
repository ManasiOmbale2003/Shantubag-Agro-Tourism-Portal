<?php
// Start session (optional for showing success/error messages)
session_start();

// Database connection
$servername = "localhost";
$username   = "root";       // change if needed
$password   = "";           // change if needed
$dbname     = "shantubag_db";  // change to your DB name
$port       = 3307;         // change if your MySQL runs on a different port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error   = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $rating  = trim($_POST['rating']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($rating) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, rating, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $rating, $message);

        if ($stmt->execute()) {
            $success = "✅ Thank you for your feedback!";
        } else {
            $error = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "⚠️ Please fill in all fields.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Message Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-container {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 350px;
    }
    h2 {
      text-align: center;
      color: #2e7d32;
    }
    input, select, textarea, button {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background: #2e7d32;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background: #1b5e20;
    }
    .success {
      color: green;
      text-align: center;
      margin-bottom: 10px;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>🌟 Message</h2>

    <!-- Success / Error Messages -->
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <!-- Feedback Form -->
    <form action="" method="POST">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      
      <select name="rating" required>
        <option value="">-- Rate Us --</option>
        <option value="Excellent">Excellent</option>
        <option value="Good">Good</option>
        <option value="Average">Average</option>
        <option value="Poor">Poor</option>
      </select>
      
      <textarea name="message" placeholder="Write your feedback..." required></textarea>
      
      <button type="submit">Submit Feedback</button>
    </form>
  </div>
</body>
</html>
