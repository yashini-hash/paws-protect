<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donation</title>
    <style>
        body{ font-family: Arial; margin:0;  padding:50px;  background:#FFF8E7; }
        .donation-box{ width:450px; padding:25px; background:#ddbc8b;  align-items: center;border-radius:12px; margin:50px auto; box-shadow:0 10px 25px rgba(0,0,0,0.15); }
        label{ font-weight:bold; }
        input, select{ width:100%; padding:10px; margin:8px 0 15px 0; border-radius:5px; border:1px solid #999; }
        button{ width:100%; padding:12px;  background: #5C3A21; color:#fff; border:none; border-radius:6px; cursor:pointer; }
        button:hover{  background:#9d6e4c; }
        .error{ color:red; font-size:14px; margin-bottom:10px; text-align:center;}
        #overlay{ display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; backdrop-filter:blur(5px); background: rgba(0,0,0,0.4); z-index:9998; }
        #cardModal{ display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:30px; width:90%; max-width:420px; border-radius:12px; z-index:9999; text-align:center; }
        #otpSection{ display:none; margin-top:10px; }
        #result{ margin-top:15px; font-weight:bold; text-align:center; }
         .page-title {
            text-align: center;
            color: #5C3A21;
            font-size:30px;
            margin: 30px 0 15px;
        }

    </style>
</head>
<body>
<h2 class="page-title">Donation for Animals</h2>
<h2>Donate</h2>

<div class="donation-box">
    <label>Rescue Center</label>
    <select id="rescue_name">
        <option value="">-- Select Rescue Center --</option>
        <option value="animalcare">Animal Care</option>
        <option value="animal_care_love">Animal Care and Love</option>
    </select>

    <label>Amount (LKR)</label>
    <input type="number" id="amount" placeholder="Enter amount">

    <p class="error" id="error_msg"></p>

    <button onclick="openPayment()">Done</button>
</div>

<div id="overlay"></div>

<div id="cardModal">
    <h3>Card Payment</h3>
    <input type="text" placeholder="Card Number">
    <input type="text" placeholder="MM/YY">
    <input type="text" placeholder="CVV">
    <input type="text" placeholder="Card Holder Name">
    <input type="text" placeholder="Phone Number" id="phone_number">
    <button onclick="sendOTP()">Send OTP</button>

    <div id="otpSection">
        <input type="text" placeholder="Enter OTP" id="otp">
        <button onclick="verifyOTP()">Verify OTP</button>
    </div>

    <p id="result"></p>
</div>

<script>
function openPayment(){
    let rescue = document.getElementById("rescue_name").value;
    let amount = document.getElementById("amount").value;
    let error = document.getElementById("error_msg");
    error.innerHTML="";
    if(rescue=="" || amount==""){
        error.innerHTML="Please select rescue center and enter amount ❌";
        return;
    }
    document.getElementById("overlay").style.display="block";
    document.getElementById("cardModal").style.display="block";
}

function sendOTP(){
    let phone = document.getElementById("phone_number").value.trim();
    if(phone==""){ alert("Enter phone number"); return; }

    fetch("send_otp.php",{
        method:"POST",
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:"phone="+encodeURIComponent(phone)
    })
    .then(res=>res.text())
    .then(data=>{
        if(data=="sent"){
            document.getElementById("otpSection").style.display="block";
            document.getElementById("result").innerHTML="OTP sent ✅";
        } else {
            document.getElementById("result").innerHTML="Error sending OTP  <br>"+data;
        }
    });
}

function verifyOTP(){
    let otp = document.getElementById("otp").value.trim();
    if(otp==""){ alert("Enter OTP"); return; }

    fetch("verify_otp.php",{
        method:"POST",
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:"otp="+encodeURIComponent(otp)
    })
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("result").innerHTML=data;
    });
}
</script>

</body>
</html>
