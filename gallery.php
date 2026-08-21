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
  <title>Gallery - Shantubag Agro Tourism</title>
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
      width: 100%;
      height: 250px;
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
  <p>Our Beautiful Photo Gallery</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="gallery.php">Gallery</a>
    <a href="Package.php">Package</a>
    <a href="contact.php">Contact Us</a>
    <a href="facilities.php">Facilities</a>
    
</nav>

<!-- Gallery Content -->
<div class="container">
  <h2>Gallery of Shantubag</h2>
  <div class="gallery-grid">
    <div class="gallery-card">
      <img src="img/gallery/image1.png" alt="Gallery Image 1"> 
    </div>
    <div class="gallery-card">
      <img src="img/gallery/image2.jpg" alt="Gallery Image 2">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/image3.jpg" alt="Gallery Image 3">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/image4.png" alt="Gallery Image 4">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/image5.jpeg" alt="Gallery Image 5">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/image6.jpeg" alt="Gallery Image 6">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/image7.jpeg" alt="Gallery Image 7">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/image8.jpeg" alt="Gallery Image 8">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/Room1.jpeg" alt="Room 1">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/Room2.jpeg" alt="Room 2">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/Room3.jpeg" alt="Room 3">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/Room4.jpeg" alt="Room 4">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/Room5.jpeg" alt="Room 5">
    </div>
    <div class="gallery-card">
      <img src="img/gallery/Room6.jpeg" alt="Room 6">
    </div>
  </div>
</div>

<footer>
  <p>&copy; <?php echo date("Y"); ?> Shantubag Agro Tourism. All rights reserved.</p>
</footer>

</body>
</html>
