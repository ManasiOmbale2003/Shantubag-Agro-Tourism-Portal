<?php
session_start();
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password !== $cpassword) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: user_registration.php");
        exit();
    }

    // Hash password
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // INSERT new user (registered_only = Yes)
    $sql = "INSERT INTO visitors (name, email, phone, password, registered_only) 
            VALUES (?, ?, ?, ?, 'Yes')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $email, $phone, $hashed);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful!";
        header("Location: user_login.php");
        exit();
    } else {
        $_SESSION['error'] = "Database Error: " . $conn->error;
        header("Location: user_registration.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Registration</title>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #6dd5ed, #2193b0);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      width: 380px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-20px);}
      to {opacity: 1; transform: translateY(0);}
    }
    h2 {
      text-align: center;
      color: #2193b0;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #444;
    }
    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-size: 14px;
      transition: all 0.3s;
    }
    input:focus {
      border-color: #2193b0;
      box-shadow: 0 0 8px rgba(33, 147, 176, 0.4);
      outline: none;
    }
    .btn {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #2193b0, #6dd5ed);
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: bold;
      color: #fff;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn:hover {
      background: linear-gradient(135deg, #6dd5ed, #2193b0);
      transform: scale(1.03);
    }
    .login-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }
    .login-link a {
      color: #2193b0;
      font-weight: 600;
      text-decoration: none;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
    .message {
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
    }
    .error {
      color: red;
      font-size: 13px;
      margin-top: -10px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>📝 User Registration</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='message' style='color:red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<p class='message' style='color:green;'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']);
    }
    ?>

    <form action="register_process.php" method="POST" onsubmit="return validateForm()">
      <label for="fullname">Full Name</label>
      <input type="text" id="fullname" name="fullname" required>

      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Phone Number</label>
      <input type="text" id="phone" name="phone" maxlength="10" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="cpassword">Confirm Password</label>
      <input type="password" id="cpassword" name="cpassword" required>

      <p id="error-message" class="error"></p>

      <button type="submit" class="btn">Register</button>
    </form>

    <div class="login-link">
      Already have an account? <a href="user_login.php">Login here</a>
    </div>
  </div>

  <script>
    function validateForm() {
      const fullname = document.getElementById("fullname").value.trim();
      const email = document.getElementById("email").value.trim();
      const phone = document.getElementById("phone").value.trim();
      const password = document.getElementById("password").value.trim();
      const cpassword = document.getElementById("cpassword").value.trim();
      const errorMessage = document.getElementById("error-message");

      // Reset message
      errorMessage.textContent = "";

      // Full name check
      if (fullname.length < 3) {
        errorMessage.textContent = "Full name must be at least 3 characters long.";
        return false;
      }

      // Email validation
      const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
      if (!emailPattern.test(email)) {
        errorMessage.textContent = "Please enter a valid email address.";
        return false;
      }

      // Phone number validation
      const phonePattern = /^[0-9]{10}$/;
      if (!phonePattern.test(phone)) {
        errorMessage.textContent = "Phone number must be 10 digits.";
        return false;
      }

      // Password validation
      if (password.length < 6) {
        errorMessage.textContent = "Password must be at least 6 characters.";
        return false;
      }

      // Confirm password match
      if (password !== cpassword) {
        errorMessage.textContent = "Passwords do not match.";
        return false;
      }

      return true; // ✅ Allow form to submit
    }
  </script>

</body>
</html>
