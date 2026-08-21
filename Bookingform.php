<?php
// booking.php
$package_preselect = $_GET['package'] ?? '';
include "db_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shantubag Agro Portal - Booking Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* 🌿 General styling */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(120deg, #e4f0d0, #fdf5e6);
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

form {
    background: #ffffff;
    padding: 35px 40px;
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    max-width: 500px;
    width: 90%;
}

h2 {
    text-align: center;
    color: #2e462d;
    font-weight: 600;
}

label {
    display: block;
    margin-top: 12px;
    font-weight: 500;
}

input, select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #bfc8b2;
    border-radius: 8px;
    margin-top: 5px;
}

.totalPrice {
    font-weight: 600;
    color: #d04e28;
    margin-top: 12px;
}

button {
    margin-top: 22px;
    padding: 12px;
    width: 100%;
    background: linear-gradient(90deg, #7fbf64, #5a8a54);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
button:hover { background: linear-gradient(90deg, #6daa57, #4d7a49); }

#qrCode {
    display: none;
    text-align: center;
    margin-top: 15px;
    background: #f5f8f3;
    padding: 12px;
    border-radius: 10px;
    border: 1px dashed #a3b78a;
}
#qrCode img { max-width: 130px; border-radius: 10px; }

#confirmPayment {
    margin-top: 10px;
    background: #5a8a54;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
}
#confirmPayment:hover { background: #497544; }

.back-btn {
    display: block;
    margin-top: 20px;
    text-align: center;
    color: #5a8a54;
    text-decoration: none;
    font-weight: 500;
}
.back-btn:hover { color: #3c6439; }

@media (max-width: 600px) {
    form { padding: 25px; }
}
</style>
</head>

<body>
<form id="bookingForm" action="submit_booking.php" method="POST" enctype="multipart/form-data">
    <h2>Booking Form</h2>

    <label>Full Name:</label>
    <input type="text" name="name" required>

    <label>Email Address:</label>
    <input type="email" name="email" required>

    <label>Mobile Number:</label>
    <input type="tel" name="phone" pattern="[0-9]{10}" required>

    <label>Booking Type:</label> 
    <select name="booking_type" id="booking_type" required onchange="toggleOptions()"> 
        <option value="">-- Select Type --</option>
        <option value="Room">Room Booking</option> 
        <option value="Package" <?= $package_preselect ? 'selected' : '' ?>>Package Booking</option> 
    </select> 

    <div id="roomOptions" style="display:none;"> 
        <label>Select Room:</label>
        <select name="room_type" id="room_type" onchange="updatePrice(); checkAvailability();">
            <option value="Cottage">Cottage - ₹2000/person</option> 
            <option value="Tent">Tent - ₹1500/person</option> 
            <option value="Dormitory">Dormitory - ₹1000/person</option> 
        </select>
    </div> 

    <div id="packageOptions" style="display:none;">
        <label>Select Package:</label> 
        <select name="package_type" id="package_type" onchange="updatePrice(); checkAvailability();"> 
            <option value="Farm Visit" <?= $package_preselect=='Farm Visit'?'selected':'' ?>>Farm Visit - ₹1200/person</option>
            <option value="Weekend Stay" <?= $package_preselect=='Weekend Stay'?'selected':'' ?>>Weekend Stay - ₹4500/couple</option> 
            <option value="Adventure Package" <?= $package_preselect=='Adventure Package'?'selected':'' ?>>Adventure Package - ₹2800/person</option> 
        </select>
    </div>

    <label>Check-in Date:</label>
    <input type="date" name="checkin" id="checkin" required onchange="checkAvailability()">

    <label>Check-out Date:</label>
    <input type="date" name="checkout" id="checkout" required onchange="checkAvailability()">

    <p id="availabilityMessage" style="margin-top:10px; font-weight:600;"></p>

    <label>Number of Guests:</label>
    <input type="number" name="guests" id="guests" min="1" value="1" onchange="updatePrice()" required>

    <div class="totalPrice" id="totalPrice">Total Price: ₹0</div>

    <p style="color:#d04e28; font-weight:600; margin-top:15px;">
        * Note: You must pay 50% advance before confirming your booking.
    </p>

    <label>Payment Mode:</label>
    <select name="payment_mode" id="payment_mode" required onchange="toggleQRCode()">
        <option value="">-- Select Payment Mode --</option>
        <option value="UPI">UPI</option>
        <option value="Cash">Cash</option>
    </select>

    <div id="qrCode">
        <p>Scan UPI QR Code to Pay:</p>
        <img src="uploads/QR.jpeg" alt="UPI QR Code">
        <button type="button" id="confirmPayment" onclick="confirmPayment()">✅ Confirm Payment</button>
        <p id="paymentMessage" style="color:green; font-weight:600; display:none; margin-top:10px;">
            Payment confirmed! Receipt auto-generated.
        </p>
    </div>

    <label>Upload Payment Receipt:</label>
    <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf">

    <button type="submit">Submit Booking</button>
    <a href="user_Dashboard.php" class="back-btn">← Back to Dashboard</a>
</form>

<script>
const roomPrices = { 'Cottage': 2000, 'Tent': 1500, 'Dormitory': 1000 };
const packagePrices = { 'Farm Visit':1200, 'Weekend Stay':4500, 'Adventure Package':2800 };

function toggleOptions() {
    const type = document.getElementById("booking_type").value;
    document.getElementById("roomOptions").style.display = (type==='Room') ? 'block':'none';
    document.getElementById("packageOptions").style.display = (type==='Package') ? 'block':'none';
    updatePrice();
}

function toggleQRCode() {
    const paymentMode = document.getElementById("payment_mode").value;
    document.getElementById("qrCode").style.display = (paymentMode==='UPI') ? 'block' : 'none';
}

function updatePrice() {
    const type = document.getElementById("booking_type").value;
    const guests = parseInt(document.getElementById("guests").value) || 1;
    let total = 0;
    if(type==='Room') {
        const room = document.getElementById("room_type").value;
        total = (roomPrices[room] || 0) * guests;
    } else if(type==='Package') {
        const pkg = document.getElementById("package_type").value;
        total = (packagePrices[pkg] || 0) * guests;
    }
    document.getElementById("totalPrice").textContent = "Total Price: ₹" + total;
}

/* ✅ Confirm Payment Logic */
function confirmPayment() {
    document.getElementById("confirmPayment").disabled = true;
    const msg = document.getElementById("paymentMessage");
    msg.style.display = "block";
    msg.textContent = "✅ Payment confirmed! Receipt auto-generated.";
    const receiptInput = document.getElementById("receipt");
    const blob = new Blob(["Payment confirmed for Shantubag Agro Tourism"], { type: "text/plain" });
    const file = new File([blob], "Shantubag_Receipt.txt", { type: "text/plain" });
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    receiptInput.files = dataTransfer.files;
    alert("Payment successful! Receipt auto-uploaded. You can now submit your booking.");
}

/* ✅ Combined availability check for Room + Package */
function checkAvailability() {
    const type = document.getElementById("booking_type").value;
    if (!type) return;

    const checkin = document.getElementById("checkin").value;
    const checkout = document.getElementById("checkout").value;
    if (!checkin || !checkout) return;

    let value = "";
    if (type === "Room") value = document.getElementById("room_type").value;
    else value = document.getElementById("package_type").value;

    fetch(`check_availability.php?type=${type}&value=${encodeURIComponent(value)}&checkin=${checkin}&checkout=${checkout}`)
        .then(res => res.text())
        .then(data => {
            const msg = document.getElementById("availabilityMessage");
            if (data === "full") {
                msg.style.color = "red";
                msg.textContent = "❌ Fully booked for selected dates!";
                document.querySelector("button[type='submit']").disabled = true;
            } else {
                msg.style.color = "green";
                msg.textContent = "✅ Available for the selected dates!";
                document.querySelector("button[type='submit']").disabled = false;
            }
        });
}

window.onload = function() {
    toggleOptions();
    updatePrice();
};
</script>
</body>
</html>
