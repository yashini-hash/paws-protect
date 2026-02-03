<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donation</title>
        <link rel="stylesheet" href="donations.css">

</head>
<body>
<h2 class="page-title">Donation for Animals</h2>

<div class="donation-box">
    <label>Rescue Center</label>
    <select id="rescue_name">
        <option value="">-- Select Rescue Center --</option>
        <?php
        $query = "SELECT rescue_center_id, center_name FROM rescue_center WHERE status='active' ORDER BY center_name ASC";
        $result = mysqli_query($conn, $query);
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                echo '<option value="'.htmlspecialchars($row['rescue_center_id']).'">'.htmlspecialchars($row['center_name']).'</option>';
            }
        }
        ?>
    </select>

    <label>Amount (LKR)</label>
    <input type="number" id="amount" placeholder="Enter amount">

    <label>Your Name</label>
    <input type="text" id="donor_name" placeholder="Your Name">

    <label>Email</label>
    <input type="email" id="donor_email" placeholder="Email">

    <label>Phone Number</label>
    <input type="text" id="phone_number" placeholder="Enter phone number">

    <p class="error" id="error_msg"></p>

    <button onclick="openPayment()">Donate</button>
</div>

<div id="overlay"></div>

<div id="cardModal">
    <h3>Card Payment</h3>
    <input type="text" placeholder="Card Number" id="card_number">
    <input type="text" placeholder="MM/YY" id="expiry">
    <input type="text" placeholder="CVV" id="cvv">
    <input type="text" placeholder="Card Holder Name" id="card_holder">
    <button onclick="validateCardAndSendOTP()">Send OTP</button>

    <div id="otpSection">
        <input type="text" placeholder="Enter OTP" id="otp">
        <button onclick="verifyOTP()">Verify OTP</button>
    </div>

    <p id="result"></p>
</div>

<script>
function openPayment() {
    let rescue = document.getElementById("rescue_name").value.trim();
    let amount = document.getElementById("amount").value.trim();
    let donor_name = document.getElementById("donor_name").value.trim();
    let donor_email = document.getElementById("donor_email").value.trim();
    let phone = document.getElementById("phone_number").value.trim();
    let error = document.getElementById("error_msg");

    if (!rescue || !amount || !donor_name || !donor_email || !phone) {
        error.innerHTML = "Please fill all fields";
        return;
    }

    if (isNaN(amount) || amount <= 0) {
        error.innerHTML = "Please enter a valid amount";
        return;
    }
    if (amount < 500) {
        error.innerHTML = "Maximum donation amount is LKR 500";
        return;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(donor_email)) {
        error.innerHTML = "Please enter a valid email address";
        return;
    }

    const phonePattern = /^[0-9]{10}$/;
    if (!phonePattern.test(phone)) {
        error.innerHTML = "PPlease enter a valid Phone number ";
        return;
    }

    error.innerHTML = "";
    document.getElementById("overlay").style.display = "block";
    document.getElementById("cardModal").style.display = "block";
}

document.getElementById("overlay").addEventListener("click", function(){
    document.getElementById("overlay").style.display = "none";
    document.getElementById("cardModal").style.display = "none";
    document.getElementById("otpSection").style.display = "none";
    document.getElementById("result").innerHTML = "";
});

function validateCardAndSendOTP(){
    let cardNumber = document.getElementById("card_number").value.trim();
    let expiry     = document.getElementById("expiry").value.trim();
    let cvv        = document.getElementById("cvv").value.trim();
    let holder     = document.getElementById("card_holder").value.trim();
    let result     = document.getElementById("result");

    if(!/^[0-9]{16}$/.test(cardNumber)){
        result.innerHTML = "Invalid card number";
        return;
    }

    if(!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)){
        result.innerHTML = "Invalid expiry (MM/YY)";
        return;
    }

    if(!/^[0-9]{3}$/.test(cvv)){
        result.innerHTML = "Invalid CVV";
        return;
    }

    if(!/^[A-Za-z ]{3,}$/.test(holder)){
        result.innerHTML = "Enter card holder name";
        return;
    }

    result.innerHTML = "";
    sendOTP();
}

function sendOTP(){
   let rescue = document.getElementById("rescue_name").value;
     let amount = document.getElementById("amount").value; 
     let phone = document.getElementById("phone_number").value;
      let donor_name = document.getElementById("donor_name").value; 
      let donor_email = document.getElementById("donor_email").value;
       fetch(
        'send_otp.php', { 
            method:'POST', 
            headers:{ 'Content-Type':'application/x-www-form-urlencoded' }, 
           body: `rescue_center=${rescue}&amount=${amount}&phone=${phone}&donor_name=${encodeURIComponent(donor_name)}&donor_email=${encodeURIComponent(donor_email)}`
        }) .then(response => response.text()) .then(data => { if(data.trim() === 'sent')
        { document.getElementById("otpSection").style.display = "block"; 
        document.getElementById("result").innerHTML = "OTP sent "; 

        } 
        else { document.getElementById("result").innerHTML = "Failed to send OTP "; } 
    });
 }


function verifyOTP(){
    let otp = document.getElementById("otp").value;

    fetch('verify_otp.php', {
        method:'POST',
        headers:{ 'Content-Type':'application/x-www-form-urlencoded' },
        body: `otp=${otp}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("result").innerHTML = data;
        if(data.includes("successful")){
            document.getElementById("otpSection").style.display = "none";
        }
    });
}
</script>
</body>
</html>
