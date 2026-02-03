<?php
session_start();
include("../page/dbconnect.php");
<<<<<<< HEAD
date_default_timezone_set('Asia/Colombo');
=======
date_default_timezone_set('Asia/Colombo'); 

>>>>>>> d962b5d91a7aa0f0158bb2cabdd292da779b5fbd

if(empty($_POST['phone']) || empty($_POST['rescue_center']) || empty($_POST['amount'])){
    echo "fail";
    exit;
}

$rescue_id = $_POST['rescue_center'];

$query = "SELECT center_name FROM rescue_center WHERE rescue_center_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rescue_id);
$stmt->execute();
$result = $stmt->get_result();
$rescue_name = $result->fetch_assoc()['center_name'] ?? "Unknown Center";

$_SESSION['rescue_center'] = $rescue_name;

$_SESSION['amount'] = $_POST['amount'];
$_SESSION['phone'] = $_POST['phone'];
$_SESSION['donor_name'] = $_POST['donor_name'] ?? NULL;
$_SESSION['donor_email'] = $_POST['donor_email'] ?? NULL;

$phone = $_POST['phone'];
if(substr($phone,0,1)=="0"){
    $phone = "94".substr($phone,1);
}

$otp = rand(100000,999999);
$_SESSION['otp'] = $otp;

$userId   = "30933";
$apiKey   = "NQAoZfPFrRX1CVdxYxYL";
$senderId = "NotifyDEMO";
$message  = "Your OTP code is: $otp";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://app.notify.lk/api/v1/send");
curl_setopt($ch, CURLOPT_POST, true);
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
}else{
    echo "fail";
}
?>
