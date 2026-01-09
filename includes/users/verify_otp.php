<?php
session_start();

include("../page/dbconnect.php");

if(!isset($_POST['otp']) || empty($_POST['otp'])){
    echo "Error: OTP not received!";
    exit;
}

$enteredOTP = $_POST['otp'];

if(isset($_SESSION['otp']) && $enteredOTP == $_SESSION['otp']){
    echo "OTP verified ✅ Payment successful!";
    unset($_SESSION['otp']); // Clear OTP
} else {
    echo "Invalid OTP ❌";
}
?>
