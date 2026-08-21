<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shantubag Agro Portal - Package</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f9f9f9, #dfe9f3);
      color: #333;
    }
    header {
      background: #1a676dff;
      padding: 20px;
      text-align: center;
      color: white;
      font-size: 20px;
      font-weight: bold;
      letter-spacing: 2px;
    }
    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
      padding: 40px;
    }
    .package-card {
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      transition: 0.3s;
      text-align: center;
    }
    .package-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.2);
    }
    .package-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }
    .package-card h3 {
      margin: 15px 0 10px;
      color: #639fffff;
      font-size: 22px;
    }
    .package-card p {
      padding: 0 15px;
      font-size: 15px;
      color: #555;
    }
    .price {
      font-size: 18px;
      color: #bedd5aff;
      font-weight: bold;
      margin: 10px 0;
    }
    .btn {
      display: inline-block;
      margin: 15px 0 20px;
      padding: 12px 25px;
      background: #7df5e3ff;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: 0.3s;
    }
    .btn:hover {
      background: #48bcd9ff;
    }
  </style>
</head>
<body>

<header>🌿 Shantubag Agro Portal - Packages 🌿</header>

<div class="container">

  <!-- Farm Visit -->
  <div class="package-card">
    <img src="img/package/farmvisit.jpeg" alt="Farm Visit">
    <h3>Farm Visit</h3>
    <p>Experience the beauty of rural life with guided tours of lush farms, organic farming demonstrations, and a taste of authentic village hospitality.</p>
    <div class="price">₹1,200 / Person</div>
    <!-- Redirect to booking form with pre-selected package -->
    <a href="Bookingform.php?package=Farm Visit" class="btn">Book Now</a>
  </div>

  <!-- Weekend Stay -->
  <div class="package-card">
    <img src="img/package/weekend.png" alt="Weekend Stay">
    <h3>Weekend Stay</h3>
    <p>Relax with a 2-night stay in cozy cottages, enjoy bonfires, cultural programs, and farm-fresh meals. Perfect for family & friends!</p>
    <div class="price">₹4,500 / Couple</div>
    <a href="Bookingform.php?package=Weekend Stay" class="btn">Book Now</a>
  </div>

  <!-- Adventure Package -->
  <div class="package-card">
    <img src="img/package/adventure.jpeg" alt="Adventure Package">
    <h3>Adventure Package</h3>
    <p>Thrilling outdoor activities like trekking, bullock cart rides, ropeway swings, and much more for those who seek adventure.</p>
    <div class="price">₹2,800 / Person</div>
    <a href="Bookingform.php?package=Adventure Package" class="btn">Book Now</a>
  </div>

  <!-- Custom Package -->
  <div class="package-card">
    <img src="img/package/custom.jpeg" alt="Custom Package">
    <h3>Custom Package</h3>
    <p>Create your own unique agro experience by combining farm activities, adventure, and leisure according to your preference.</p>
    <div class="price">Contact Us</div>
    <a href="Bookingform.php" class="btn">Enquire Now</a>
  </div>

</div>

    <a href="user_Dashboard.php">Back</a>

</body>
</html>
