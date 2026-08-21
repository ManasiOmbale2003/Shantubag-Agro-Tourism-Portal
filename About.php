<?php
// about.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Shantubag Agro Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #16a085;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            padding: 15px 20px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #1abc9c;
        }

        .content {
            padding: 60px 20px;
            max-width: 1000px;
            margin: auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .content h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 40px;
        }

        .about-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .about-container img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .about-container > div {
            flex: 1 1 300px;
            min-width: 280px;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<style>
/* ===== Reset & Base ===== */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Segoe UI', sans-serif; line-height:1.6; background-color: #F5FFF0; color:#3A3A3A; }

/* ===== Header ===== */
header {
    background: linear-gradient(135deg, #0B3D2E, #2AA876); /* Forest Green to Leaf Green */
    color: #FFFFFF;
    padding: 12px 0;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    position: relative;
    z-index: 10;
}
header h1 { font-size: 2.8rem; margin-bottom: 8px; text-shadow: 1px 1px 3px rgba(0,0,0,0.3); }
header p { font-size: 1.2rem; }

/* ===== Navigation ===== */
nav {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    background: linear-gradient(90deg, #2AA876, #5DD39E); /* gradient nav bar */
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    border-radius: 0 0 15px 15px;
}
nav a {
    color: #FFFFFF;
    padding: 15px 25px;
    text-decoration: none;
    font-weight: 500;
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
    background-color: #FFF;
    transition: width 0.3s ease;
    border-radius: 2px;
}
nav a:hover::after { width: 50%; }
nav a:hover { color: #F0FFF0; transform: translateY(-3px); }

/* ===== Hero Section ===== */
.hero {
    background: url('img/gallery/image11.jpeg') no-repeat center center/cover;
    height: 80vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
    padding: 0 20px;
    color: #FFFFFF;
}
.hero::after {
    content: "";
    position: absolute; top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.35);
    z-index:0;
}
.hero h1, .hero p { position: relative; z-index:1; }
.hero h1 { font-size: 3rem; margin-bottom: 15px; }
.hero p { font-size: 1.2rem; max-width:600px; }

/* ===== Content Sections ===== */
section.content {
    background-color: #F5FFF0;
    padding: 60px 20px;
    max-width: 1000px;
    margin: 40px auto;
    border-radius: 12px;
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}
section.content h2 {
    color: #0B3D2E;
    text-align: center;
    margin-bottom: 40px;
}
section.content p { font-size: 1.1rem; line-height:1.8; color:#3A3A3A; }
section.content img { width: 100%; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }

/* ===== Flex Layout for About & Visit ===== */
.flex-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    align-items: center;
}
.flex-item { flex:1 1 300px; min-width:280px; }

/* ===== Map Section ===== */
.map-button { text-align: center; margin-top: 20px; }
.map-button a {
    display: inline-block;
    background-color: #5DD39E;
    color: #fff;
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 10px;
    transition: background 0.3s ease;
}
.map-button a:hover { background-color: #2AA876; }

/* ===== Footer ===== */
footer {
    background-color: #0B3D2E;
    color: #FFFFFF;
    text-align: center;
    padding: 15px 0;
}

/* ===== Responsive ===== */
@media(max-width:768px){
    .hero h1 { font-size: 2rem; }
    .hero p { font-size: 1rem; }
    .flex-container { flex-direction: column; }
}
</style>
<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="gallery.php">Gallery</a>
    <a href="Package.php">Package</a>
    <a href="contact.php">Contact Us</a>
    <a href="facilities.php">Facilities</a>
    
</nav>
<section class="content">
    <h2>About Us</h2>
    <div class="about-container">
        <div>
            <img src="img/gallery/image1.png." alt="About Us Image">
        </div>
        <div>
            <p style="font-size: 18px; color: #555; line-height: 1.8;">
                Since our inception in 2016, we have been dedicated to offering our guests an authentic and enriching rural experience, where nature, tradition, and hospitality come together. At the heart of our journey is Adinath Ombale, our esteemed mentor. With extensive expertise in natural farming and a passion for empowering others, he has been a guiding light in our growth.
            </p>
        </div>
    </div>
</section>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Shantubag Agro Portal. All rights reserved.</p>
</footer>

</body>
</html>
