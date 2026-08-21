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

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shantubag Agro Portal</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
<style>
/* ===== Reset & Base ===== */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Poppins', sans-serif; line-height:1.6; background-color: #F5FFF0; color:#333; }

/* ===== Header ===== */
header {
    background: linear-gradient(135deg, #0B3D2E, #2AA876);
    color: #fff;
    padding: 20px 0;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
header h1 { font-size: 3rem; margin-bottom: 8px; letter-spacing: 1px; }
header p { font-size: 1.2rem; }

/* ===== Navigation ===== */
nav {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    background: #ffffff;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    border-radius: 0 0 15px 15px;
}
nav a {
    color: #0B3D2E;
    padding: 15px 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}
nav a::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 5px;
    transform: translateX(-50%);
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, #2AA876, #5DD39E);
    transition: width 0.3s ease;
    border-radius: 2px;
}
nav a:hover::after { width: 50%; }
nav a:hover { color: #2AA876; transform: translateY(-3px); }

/* ===== Hero Section ===== */
.hero {
    background: url('img/gallery/image11.jpeg') no-repeat center center/cover;
    height: 85vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
    color: #fff;
    padding: 0 20px;
}
.hero::after {
    content: "";
    position: absolute; top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.45);
    z-index:0;
}
.hero h1, .hero p, .hero a { position: relative; z-index:1; }
.hero h1 { font-size: 3.2rem; margin-bottom: 15px; animation: fadeDown 1.2s ease-in-out; }
.hero p { font-size: 1.3rem; max-width:600px; margin-bottom: 20px; animation: fadeUp 1.5s ease-in-out; }
.hero a {
    background: linear-gradient(135deg, #2AA876, #5DD39E);
    padding: 12px 28px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    color: #fff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}
.hero a:hover { transform: translateY(-4px); background: linear-gradient(135deg, #0B3D2E, #2AA876); }

/* ===== Content Sections ===== */
section.content {
    background: #fff;
    padding: 60px 20px;
    max-width: 1100px;
    margin: 50px auto;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    animation: fadeIn 1.2s ease-in-out;
}
section.content h2 {
    color: #0B3D2E;
    text-align: center;
    margin-bottom: 40px;
    font-size: 2rem;
}
section.content p { font-size: 1.1rem; line-height:1.8; }
section.content img { width: 100%; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }

/* ===== Flex Layout ===== */
.flex-container {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: space-between;
    align-items: center;
}
.flex-item { flex:1 1 300px; }

/* ===== Map Section ===== */
.map-button { text-align: center; margin-top: 25px; }
.map-button a {
    display: inline-block;
    background: linear-gradient(135deg, #2AA876, #5DD39E);
    color: #fff;
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}
.map-button a:hover { background: linear-gradient(135deg, #0B3D2E, #2AA876); }

/* ===== Footer ===== */
footer {
    background-color: #0B3D2E;
    color: #FFFFFF;
    text-align: center;
    padding: 20px 0;
    margin-top: 40px;
}
footer .social {
    margin: 12px 0;
}
footer .social a {
    color: #fff;
    margin: 0 10px;
    font-size: 1.2rem;
    transition: color 0.3s ease;
}
footer .social a:hover { color: #5DD39E; }

/* ===== Animations ===== */
@keyframes fadeIn { from {opacity:0; transform: translateY(20px);} to {opacity:1; transform: translateY(0);} }
@keyframes fadeDown { from {opacity:0; transform: translateY(-30px);} to {opacity:1; transform: translateY(0);} }
@keyframes fadeUp { from {opacity:0; transform: translateY(30px);} to {opacity:1; transform: translateY(0);} }

/* ===== Responsive ===== */
@media(max-width:768px){
    .hero h1 { font-size: 2rem; }
    .hero p { font-size: 1rem; }
    .flex-container { flex-direction: column; }
}
</style>
</head>
<body>

<header>
    <h1>Shantubag Agro Portal</h1>
    <p>Explore Nature, Enjoy Village Life</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="gallery.php">Gallery</a>
    <a href="Package.php">Package</a>
    <a href="contact.php">Contact Us</a>
    <a href="facilities.php">Facilities</a>
    <a href="Register.php">Book Your Stay</a>
    <a href="admin_login.php">Admin Login</a>
</nav>

<section class="hero">
    <h1>Welcome to Our Farm</h1>
    <p>Our village, our freshness, your delight.</p>
</section>

<section class="content">
    <h2>About Us</h2>
    <div class="flex-container">
        <div class="flex-item">
            <img src="img/gallery/profile.jpg" alt="About Us Image">
        </div>
        <div class="flex-item">
            <p>
                Since our inception in 2016, we have been dedicated to offering our guests an authentic and enriching rural experience, where nature, tradition, and hospitality come together. At the heart of our journey is <b>Adinath Ombale</b>, our esteemed mentor. With extensive expertise in natural farming and a passion for empowering others, he has been a guiding light in our growth.
            </p>
        </div>
    </div>
</section>

<section class="content">
    <h2>Visit Us</h2>
    <div style="width:100%; border-radius:12px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
       <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.648230243677!2d73.8523457!3d18.6368926!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2bfc123456789%3A0xa1b2c3d4e5f6g7h8!2sShantubag%20Agro%20Tourism!5e0!3m2!1sen!2sin!4v1721234567890!5m2!1sen!2sin"
          width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
       </iframe>
    </div>
    <div class="map-button">
        <a href="https://maps.app.goo.gl/T1ss8ithpptpLPaB6" target="_blank">Open in Google Maps</a>
    </div>
</section>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Shantubag Agro Portal. All rights reserved.</p>
    <div class="social">
        <a href="#"><i class="fab fa-facebook"></i>🌐</a>
        <a href="#"><i class="fab fa-instagram"></i>📷</a>
        <a href="#"><i class="fab fa-twitter"></i>🐦</a>
    </div>
</footer>

</body>
</html>
