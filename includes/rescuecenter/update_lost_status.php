<?php
session_start();
include("../page/dbconnect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    empty($_SESSION['user_id']) ||
    empty($_SESSION['role']) ||
    $_SESSION['role'] !== 'rescuecenter'
) {
    session_unset();
    session_destroy();
    header("Location: /paws&protect/includes/page/login.php");
    exit();
}

if (!isset($_POST['lost_id'], $_POST['status'])) {
    exit("Invalid request");
}

$lost_id = intval($_POST['lost_id']);
$status  = $_POST['status'];

if (!in_array($status, ['found', 'notfound'])) {
    exit("Invalid status");
}

$stmt = $conn->prepare("UPDATE lost_animals SET status = ? WHERE lost_id = ?");
$stmt->bind_param("si", $status, $lost_id);
$stmt->execute();

$stmt2 = $conn->prepare("
    SELECT u.name AS owner_name, u.email AS owner_email
    FROM lost_animals l
    INNER JOIN users u ON l.user_id = u.user_id
    WHERE l.lost_id = ?
");
$stmt2->bind_param("i", $lost_id);
$stmt2->execute();
$owner = $stmt2->get_result()->fetch_assoc();

$owner_name  = $owner['owner_name'] ?? '';
$owner_email = $owner['owner_email'] ?? '';

if(!empty($owner_email)){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'animalcarelove01@gmail.com';
        $mail->Password   = 'ncufnnhhoezkbkcp'; // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('animalcarelove01@gmail.com', 'Paws & Protect');
        $mail->addAddress($owner_email, $owner_name);

        $mail->isHTML(false);
        $mail->Subject = "Update on Your Lost Animal";
        if($status == 'found'){
            $mail->Body = "Hi $owner_name,\n\nGood news! Your lost animal has been marked as FOUND.\n\n— Paws & Protect Team";
        } else {
            $mail->Body = "Hi $owner_name,\n\nStatus update: Your lost animal is still not found.\n\n— Paws & Protect Team";
        }

        $mail->send();
        $msg = "Status updated and email sent to owner.";
    } catch (Exception $e) {
        $msg = "Status updated, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $msg = "Status updated, but owner email not found.";
}

header("Location: lost_animal_view.php?lost_id=$lost_id&msg=" . urlencode($msg));
exit;
?>
