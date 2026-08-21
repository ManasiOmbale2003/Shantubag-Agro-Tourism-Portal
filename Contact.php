
<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "shantubag_db", 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $message = $_POST["message"];

    $sql = "INSERT INTO contacts (name, email, phone, message) 
            VALUES ('$name', '$email', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Thank you! Your message has been sent.";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Shantubag Agro Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f9f7;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
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

        nav a:hover,
        nav a.active {
            background-color: #1abc9c;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .contact-info {
            font-size: 18px;
            color: #333;
            line-height: 1.8;
        }

        .contact-info span {
            font-weight: bold;
            color: #16a085;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Shantubag Agro Tourism</h1>
    <p>Contact Us</p>
</header>
<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="gallery.php">Gallery</a>
    <a href="Package.php">Package</a>
    <a href="contact.php">Contact Us</a>
    <a href="facilities.php">Facilities</a>
    
</nav>
<div class="container">
    <h2>Get in Touch with Us</h2>
    <div class="contact-info">
        <p><span>Address:</span> Shantubag Farm, At.Post-Kedambe, Medha Bondarwadi Road, Tal. Jaoli, Dist. Satara, Maharashtra, India</p>
        <p><span>Phone:</span> +91 9309906110</p>
        <p><span>Email:</span> shantubag@gmail.com</p>
        <p><span>Visiting Hours:</span> Monday to Sunday - 9:00 AM to 6:00 PM</p>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Shantubag Agro Portal. All rights reserved.</p>
</footer>

</body>
</html>
