<?php
session_start();
include("../page/dbconnect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

if(empty($_POST['otp'])){
    echo "OTP missing!";
    exit;
}

$enteredOTP = $_POST['otp'];

if(isset($_SESSION['otp']) && $enteredOTP == $_SESSION['otp']){

    $user_id     = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : NULL;
    $rescue      = $_SESSION['rescue_center'] ?? null;
    $amount      = isset($_SESSION['amount']) ? floatval($_SESSION['amount']) : 0;
    $phone       = $_SESSION['phone'] ?? null;
    $donor_name  = $_SESSION['donor_name'] ?? "Donor";
    $donor_email = $_SESSION['donor_email'] ?? null;
    $payment_method = "Card";
    $payment_status = "Success";
    $transaction_ref = uniqid('TRX_'); 

    if(empty($rescue) || empty($amount) || empty($phone)){
        echo "Donation data is missing! ";
        exit;
    }

    $sql = "INSERT INTO donations 
            (user_id, rescue_center, amount, phone, donor_name, donor_email, payment_method, payment_status, transaction_ref)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "isdssssss",
        $user_id,
        $rescue,
        $amount,
        $phone,
        $donor_name,
        $donor_email,
        $payment_method,
        $payment_status,
        $transaction_ref
    );

    if($stmt->execute()){
        unset($_SESSION['otp']); 
        echo "OTP verified  Payment successful!";

        if($donor_email){
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'animalcarelove01@gmail.com'; 
                $mail->Password   = 'ncufnnhhoezkbkcp';           
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('animalcarelove01@gmail.com', 'Paws & Protect');
                $mail->addAddress($donor_email, $donor_name);

                $mail->isHTML(true);
                $mail->Subject = "Donation Receipt - Thank You!";
                $mail->Body = "
                <html>
                <head><title>Donation Receipt</title></head>
                <body>
                    <h2>Thank you for your donation!</h2>
                    <p>Dear ".htmlspecialchars($donor_name).",</p>
                    <p>We have received your donation successfully.</p>
                    <table border='1' cellpadding='5'>
                        <tr><th>Rescue Center</th><td>".htmlspecialchars($rescue)."</td></tr>
                        <tr><th>Amount</th><td>LKR ".number_format($amount,2)."</td></tr>
                        <tr><th>Payment Method</th><td>$payment_method</td></tr>
                        <tr><th>Transaction Reference</th><td>$transaction_ref</td></tr>
                        <tr><th>Phone</th><td>".htmlspecialchars($phone)."</td></tr>
                        <tr><th>Date</th><td>".date("Y-m-d H:i:s")."</td></tr>
                    </table>
                    <p>Thank you for supporting our rescue centers!</p>
                </body>
                </html>
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        }

    } else {
        echo "Database insert failed ❌: " . $stmt->error;
    }

} else {
    echo "Invalid OTP ";
}
?>
