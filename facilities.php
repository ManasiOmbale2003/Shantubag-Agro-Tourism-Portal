<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shantubag";
$port = 3307; // Use the actual MySQL port from your my.ini

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
// echo "✅ Connected successfully"; // Uncomment for debugging
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Facilities - Shantubag Agro Tourism</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    header {
      background: linear-gradient(90deg, #2c3e50, #34495e);
      color: white;
      padding: 20px 0;
      text-align: center;
      box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }

    nav {
      background-color: #16a085;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    nav a {
      color: white;
      padding: 15px 20px;
      text-decoration: none;
      font-weight: 500;
      transition: background 0.3s ease;
    }

    nav a:hover,
    nav a.active {
      background-color: #1abc9c;
    }

    .container {
      max-width: 1500px;
      margin: 20px auto;
      padding: 10px;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #2c3e50;
      font-size: 28px;
    }

    /* Gallery Grid */
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
    }

    /* Card Styling */
    .gallery-card {
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Image Styling */
    .gallery-card img {
      width: 200px;
      height: 300px;
      object-fit: cover;
      transition: transform 0.4s ease;
      display: block;
    }

    /* Zoom Effect on Hover */
    .gallery-card:hover img {
      transform: scale(1.07);
    }

    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
      box-shadow: 0 -3px 8px rgba(0,0,0,0.2);
    }

   @media (max-width: 500px) {
      .gallery-card img {
        height: 200px;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Shantubag Agro Tourism</h1>
  <p>Our Beautiful Facilities</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="gallery.php">Gallery</a>
    <a href="Package.php">Package</a>
    <a href="contact.php">Contact Us</a>
    <a href="facilities.php">Facilities</a>
    
</nav>

<!-- Facilities Content -->
<div class="container">
  <h2>Facilities of Shantubag</h2>
  <div class="facilities-grid">
    <div class="gallery-card">
      <img src="img/Facilities/image1.jpeg" alt="Facilities Image 1"> 
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image2.jpeg" alt="Facilities Image 2">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image3.jpeg" alt="Facilities Image 3">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image4.jpeg" alt="Facilities Image 4">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image5.png" alt="Facilities Image 5">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image6.png" alt="Facilities Image 6">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image7.png" alt="Facilities Image 7">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image8.jpeg" alt="Facilities Image 8">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image9.png" alt="Facilities Image 9">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image10.jpeg" alt="Facilities Image 10">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image11.jpeg" alt="Facilities Image 11">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/imagw12.jpeg" alt="Facilities Image 12">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image13.png" alt="Facilities Image 13">
    </div>
    <div class="facilities-card">
      <img src="img/Facilities/image14.png" alt="Facilities Image 14">
    </div>
    </div>
    </div>


  </div>
</div>

<footer>
  <p>&copy; <?php echo date("Y"); ?> Shantubag Agro Tourism. All rights reserved.</p>
</footer>

</body>
</html>
