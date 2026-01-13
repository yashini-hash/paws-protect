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
    <style>
        body{ font-family: Arial; margin:0; padding:50px; background:#FFF8E7; }
        .donation-box{ width:450px; padding:25px; background:#ddbc8b; align-items: center;border-radius:12px; margin:50px auto; box-shadow:0 10px 25px rgba(0,0,0,0.15); }
        label{ font-weight:bold; }
        input, select{ width:100%; padding:10px; margin:8px 0 15px 0; border-radius:5px; border:1px solid #999; }
        button{ width:100%; padding:12px;  background: #5C3A21; color:#fff; border:none; border-radius:6px; cursor:pointer; }
        button:hover{  background:#9d6e4c; }
        .error{ color:red; font-size:14px; margin-bottom:10px; text-align:center;}
        #overlay{ display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; backdrop-filter:blur(5px); background: rgba(0,0,0,0.4); z-index:9998; }
        #cardModal{ display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:30px; width:90%; max-width:420px; border-radius:12px; z-index:9999; text-align:center; }
        #otpSection{ display:none; margin-top:10px; }
        #result{ margin-top:15px; font-weight:bold; text-align:center; }
        .page-title { text-align: center; color: #5C3A21; font-size:30px; margin: 30px 0 15px; }

        @media (max-width: 768px) {
            body { padding:20px; }
            .donation-box { width:95%; padding:20px; margin:20px auto; }
            label,input,select,button { font-size:14px; padding:10px; }
            h2.page-title { font-size:26px; margin:20px 0 15px; }
            #cardModal { width:90%; padding:20px; }
        }
        @media (max-width: 480px) {
            .donation-box { padding:15px; }
            h2.page-title { font-size:22px; }
            button { padding:10px; font-size:14px; }
            #cardModal input { font-size:13px; }
            #cardModal h3 { font-size:18px; }
        }
    </style>
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
    <button id="sendOtpBtn" onclick="sendOTP()">Send OTP</button>

    <div id="otpSection">
        <input type="text" placeholder="Enter OTP" id="otp">
        <button onclick="verifyOTP()">Verify OTP</button>
    </div>

    <p id="result"></p>
</div>

<script>
function openPayment() {
    let rescue = document.getElementById("rescue_name").value;
    let amount = document.getElementById("amount").value;
    let phone = document.getElementById("phone_number").value;
    let error = document.getElementById("error_msg");
    error.innerHTML = "";

    if(rescue == "" || amount == "" || phone == "") {
        error.innerHTML = "Please fill all fields ❌";
        return;
    }

    document.getElementById("overlay").style.display = "block";
    document.getElementById("cardModal").style.display = "block";
}


document.getElementById("overlay").addEventListener("click", function(){
    document.getElementById("overlay").style.display = "none";
    document.getElementById("cardModal").style.display = "none";
    document.getElementById("otpSection").style.display = "none";
    document.getElementById("result").innerHTML = "";
});

function sendOTP(){
    let rescue = document.getElementById("rescue_name").value;
    let amount = document.getElementById("amount").value;
    let phone = document.getElementById("phone_number").value;
    let donor_name = document.getElementById("donor_name").value;
    let donor_email = document.getElementById("donor_email").value;

    fetch('send_otp.php', {
        method:'POST',
        headers:{ 'Content-Type':'application/x-www-form-urlencoded' },
        body: `rescue_center=${rescue}&amount=${amount}&phone=${phone}&donor_name=${encodeURIComponent(donor_name)}&donor_email=${encodeURIComponent(donor_email)}`
    })
    .then(response => response.text())
    .then(data => {
        if(data.trim() === 'sent'){
            document.getElementById("otpSection").style.display = "block";
            document.getElementById("result").innerHTML = "OTP sent ✅";
        } else {
            document.getElementById("result").innerHTML = "Failed to send OTP ❌";
        }
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
