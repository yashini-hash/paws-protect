<?php
session_start();
include("../page/dbconnect.php");

// Check phone number received
if(!isset($_POST['phone']) || empty($_POST['phone'])){
    echo "Error: Phone number not received!";
    exit;
}

$phone = $_POST['phone'];

// Convert local Sri Lankan number to international format
if(substr($phone,0,1)=="0"){
    $phone = "94".substr($phone,1);
}

// Generate OTP
$otp = rand(100000,999999);
$_SESSION['otp'] = $otp;

// Notify.lk API - Trial account
$userId = "30675";                     // Dashboard → User ID
$apiKey = "K6I5eWSGw73zpLirTDPi";      // Dashboard → API Key
$senderId = "NotifyDEMO";              // Trial sender ID from dashboard
$message = "Your OTP code is: $otp";

// cURL POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://app.notify.lk/api/v1/send");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    "user_id"   => $userId,
    "api_key"   => $apiKey,
    "sender_id" => $senderId,
    "to"        => $phone,
    "message"   => $message
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

if($result === false){
    echo "Curl error: ".curl_error($ch);
    exit;
}
curl_close($ch);

// Decode API response
$response = json_decode($result,true);

if(isset($response['status']) && $response['status']=="success"){
    echo "sent"; // Success
} else {
    echo "fail: ".$result; // Show API error
}



if(!isset($_POST['phone']) || empty($_POST['phone'])){
    echo "Error: Phone number not received!";
    exit;
}

$phone = $_POST['phone'];
if(substr($phone,0,1)=="0") $phone = "94".substr($phone,1);
$otp = rand(100000,999999);
$_SESSION['otp'] = $otp;

// Notify.lk API (trial)
$userId = "30675";
$apiKey = "K6I5eWSGw73zpLirTDPi";
$senderId = "NotifyDEMO";
$message = "Your OTP code is: $otp";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://app.notify.lk/api/v1/send");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    "user_id"   => $userId,
    "api_key"   => $apiKey,
    "sender_id" => $senderId,
    "to"        => $phone,
    "message"   => $message
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result,true);
if(isset($response['status']) && $response['status']=="success"){
    echo "sent";
} else {
    echo "fail: ".$result;
}
?>
