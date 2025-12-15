<?php
session_start();
include("../page/dbconnect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

if (!isset($_SESSION['rescue_center_id'])) {
    exit("Unauthorized");
}

$rescue_center_id = $_SESSION['rescue_center_id'];
$request_id = intval($_POST['request_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($request_id === 0 || !in_array($action, ['approve', 'reject'])) {
    exit("Invalid request");
}

// Fetch animal_id related to this request
$stmtAnimal = $conn->prepare(
    "SELECT animal_id 
     FROM adopt_requests 
     WHERE request_id = ? AND rescue_center_id = ?"
);
$stmtAnimal->bind_param("ii", $request_id, $rescue_center_id);
$stmtAnimal->execute();
$resAnimal = $stmtAnimal->get_result();

if ($resAnimal->num_rows === 0) {
    exit("Animal not found for this request");
}

$animalRow = $resAnimal->fetch_assoc();
$animal_id = $animalRow['animal_id'];

$status = ($action === 'approve') ? 'Approved' : 'Rejected';

// Update request status
$stmt = $conn->prepare(
    "UPDATE adopt_requests SET status=? WHERE request_id=? AND rescue_center_id=?"
);
$stmt->bind_param("sii", $status, $request_id, $rescue_center_id);

if ($stmt->execute()) {

    // Update animal adoption_status
    $animalStatus = ($action === 'approve') ? 'not_available' : 'available';
    $stmtUpdateAnimal = $conn->prepare(
        "UPDATE animals_details SET adoption_status=? WHERE animal_id=?"
    );
    $stmtUpdateAnimal->bind_param("si", $animalStatus, $animal_id);
    $stmtUpdateAnimal->execute();

    // Get user info for email
    $stmtUser = $conn->prepare("
        SELECT u.email, u.name, a.name AS animal_name, a.type AS animal_type
        FROM adopt_requests ar
        INNER JOIN users u ON ar.user_id = u.user_id
        INNER JOIN animals_details a ON ar.animal_id = a.animal_id
        WHERE ar.request_id = ?
    ");
    $stmtUser->bind_param("i", $request_id);
    $stmtUser->execute();
    $result = $stmtUser->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $toEmail = $user['email'];
        $userName = $user['name'];
        $animalName = $user['animal_name'];
        $type = $user['animal_type'];

        if (!empty($toEmail)) {
            $mail = new PHPMailer(true);
            try {
                // Check if SMTP can be used, otherwise fallback to mail()
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'animalcarelove01@gmail.com';
                $mail->Password   = 'ncufnnhhoezkbkcp';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('animalcarelove01@gmail.com', 'Paws & Protect');
                $mail->addAddress($toEmail, $userName);

                $mail->isHTML(false);
                $mail->Subject = "Adoption Request $status";

                if ($status === 'Approved') {
                    $mail->Body = 
"Hi $userName,

Good news! 
Your adoption request for $type - $animalName has been approved! 🐾
Please come to our rescue center within 2 days to complete the adoption process.

— The Paws & Protect Team";
                } else {
                    $mail->Body = 
"Hi $userName,

We’re sorry, but your adoption request for $type - $animalName has been rejected.
You can explore other animals available for adoption.

— The Paws & Protect Team";
                }

                // Try sending email
                $mail->send();
                echo "Request has been $status, animal status updated, and email sent.";
            } catch (Exception $e) {
                // Fallback: PHP mail() in case SMTP fails
                try {
                    $mailFallback = new PHPMailer(true);
                    $mailFallback->isMail();
                    $mailFallback->setFrom('animalcarelove01@gmail.com', 'Paws & Protect');
                    $mailFallback->addAddress($toEmail, $userName);
                    $mailFallback->Subject = "Adoption Request $status";
                    $mailFallback->Body = $mail->Body;
                    $mailFallback->send();
                    echo "Request has been $status, animal status updated, email sent via PHP mail().";
                } catch (Exception $e2) {
                    echo "Request has been $status, animal status updated, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } else {
            echo "Request has been $status and animal status updated, but user email not found.";
        }
    } else {
        echo "Request has been $status and animal status updated, but user not found.";
    }
} else {
    echo "Failed to update request";
}
?>
