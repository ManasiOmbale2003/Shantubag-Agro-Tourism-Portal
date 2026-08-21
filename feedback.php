<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "shantubag");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check login
if (!isset($_SESSION['user_id'])) {
    die("Please log in to submit feedback.");
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $package_id = $_POST['package_id'];
    $rating = $_POST['rating'];
    $comments = trim($_POST['comments']);

    $stmt = $conn->prepare("INSERT INTO feedback (user_id, package_id, rating, comments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $package_id, $rating, $comments);

    if ($stmt->execute()) {
        $message = "✅ Thank you for your feedback!";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch packages for dropdown
$packages = [];
$result = $conn->query("SELECT package_id, package_name FROM packages");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Feedback - Shantubag Agro Tourism</title>
<style>
body {
    font-family: 'Arial', sans-serif;
    background: url('assets/img/farm-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
body::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
}
.container {
    position: relative;
    z-index: 1;
    width: 90%;
    max-width: 600px;
    padding: 30px;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    color: white;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
}
input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: none;
    border-radius: 5px;
    background: rgba(255,255,255,0.8);
    color: black;
}
button {
    margin-top: 15px;
    padding: 12px;
    width: 100%;
    background: #2e8b57;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}
button:hover {
    background: #246b43;
}
.message {
    text-align: center;
    margin-bottom: 10px;
    color: #00ff99;
}
.error {
    color: yellow;
    font-size: 0.9em;
}
</style>
</head>
<body>

<div class="container">
    <h2>We Value Your Feedback</h2>
    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>
    <form id="feedbackForm" method="POST">
        <label>Your Name</label>
        <input type="text" id="name" name="name" required>
        <div class="error" id="nameError"></div>

        <label>Your Email</label>
        <input type="email" id="email" name="email" required>
        <div class="error" id="emailError"></div>

        <label>Contact Number</label>
        <input type="text" id="number" name="number" required>
        <div class="error" id="numberError"></div>

        <label>Select Package</label>
        <select name="package_id" id="package" required>
            <option value="">-- Select Package --</option>
            <?php foreach($packages as $p): ?>
                <option value="<?= $p['package_id'] ?>"><?= htmlspecialchars($p['package_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="error" id="packageError"></div>

        <label>Rating</label>
        <select name="rating" id="rating" required>
            <option value="">-- Select --</option>
            <option value="5">Excellent ⭐⭐⭐⭐⭐</option>
            <option value="4">Very Good ⭐⭐⭐⭐</option>
            <option value="3">Good ⭐⭐⭐</option>
            <option value="2">Fair ⭐⭐</option>
            <option value="1">Poor ⭐</option>
        </select>
        <div class="error" id="ratingError"></div>

        <label>Your Comments</label>
        <textarea name="comments" id="comments" rows="5" required></textarea>
        <div class="error" id="commentsError"></div>

        <button type="submit">Submit Feedback</button>
    </form>
</div>

<script>
document.getElementById('feedbackForm').addEventListener('submit', function(e) {
    let valid = true;
    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    const name = document.getElementById('name').value.trim();
    if (name.length < 2) {
        document.getElementById('nameError').textContent = "Enter a valid name.";
        valid = false;
    }

    const email = document.getElementById('email').value.trim();
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        document.getElementById('emailError').textContent = "Enter a valid email.";
        valid = false;
    }

    const number = document.getElementById('number').value.trim();
    const phonePattern = /^[0-9]{10}$/;
    if (!phonePattern.test(number)) {
        document.getElementById('numberError').textContent = "Enter a valid 10-digit phone number.";
        valid = false;
    }

    if (document.getElementById('package').value === "") {
        document.getElementById('packageError').textContent = "Please select a package.";
        valid = false;
    }

    if (document.getElementById('rating').value === "") {
        document.getElementById('ratingError').textContent = "Please select a rating.";
        valid = false;
    }

    const comments = document.getElementById('comments').value.trim();
    if (comments.length < 5) {
        document.getElementById('commentsError').textContent = "Comments should be at least 5 characters.";
        valid = false;
    }

    if (!valid) e.preventDefault();
});
</script>

</body>
</html>
