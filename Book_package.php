<?php
include("db_connect.php");

$package = isset($_GET['package']) ? $_GET['package'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $phone   = $_POST['phone'];
    $package = $_POST['package'];
    $date    = $_POST['date'];
    $persons = $_POST['persons'];

    $sql = "INSERT INTO bookings (name, email, phone, package, booking_date, persons) 
            VALUES ('$name', '$email', '$phone', '$package', '$date', '$persons')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Booking Successful!'); window.location='packages.php';</script>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Package - Shantubag Agro Tourism</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #fff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      width: 400px;
    }
    h2 { text-align: center; color: #6c63ff; }
    input, select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #6c63ff;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover { background: #5548d9; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Book Package</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone" placeholder="Phone" required>
      <input type="hidden" name="package" value="<?php echo $package; ?>">
      <p><b>Package Selected:</b> <?php echo $package; ?></p>
      <input type="date" name="date" required>
      <input type="number" name="persons" placeholder="Number of Persons" required>
      <button type="submit">Confirm Booking</button>
    </form>
  </div>
</body>
</html>
