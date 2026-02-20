<?php
include('auth.php');
include('../page/dbconnect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';


if (isset($_GET['action'], $_GET['id'])) {

    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve' || $action === 'reject') {

        $status = ($action === 'approve') ? 'active' : 'rejected';

  
        $fetch = $conn->prepare(
            "SELECT center_name, email FROM rescue_center WHERE rescue_center_id=?"
        );
        $fetch->bind_param("i", $id);
        $fetch->execute();
        $center = $fetch->get_result()->fetch_assoc();
        $fetch->close();

        if ($center) {

            $stmt = $conn->prepare(
                "UPDATE rescue_center SET status=? WHERE rescue_center_id=?"
            );
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();
            $stmt->close();

         
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
                $mail->addAddress($center['email'], $center['center_name']);
                $mail->isHTML(true);

                $mail->Subject = 'Rescue Center Application Status';

                if ($status === 'active') {
                    $mail->Body = "
                        <h3>Congratulations!</h3>
                        <p>Dear <b>{$center['center_name']}</b>,</p>
                        <p>Your rescue center registration has been <b>APPROVED</b>.</p>
                        <p>You can now log in and start using the system.</p>
                        <br>
                        <p>Regards,<br>Paws & Protect Team</p>
                    ";
                } else {
                    $mail->Body = "
                        <h3>Registration Update for Paws & Protect</h3>
                        <p>Dear <b>{$center['center_name']}</b>,</p>
         <p>We regret to inform you that your rescue center registration has not been approved at this time. Your application has been <strong>rejected</strong>.</p>
            <p>If you would like more information or guidance on how to proceed, please feel free to contact our support team. We are happy to assist you with any questions.</p>
               <br>
           <p>Best regards,<br>The Paws & Protect Team</p>
                    ";
                }

                $mail->send();

            } catch (Exception $e) {
                error_log("Mail Error: " . $mail->ErrorInfo);
            }
        }
    }

    
   header("Location: rescue_new.php?msg=" . urlencode($status));
exit;
}


include('sidebar.php');


$result = $conn->query(
    "SELECT * FROM rescue_center WHERE status='inactive' ORDER BY rescue_center_id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rescue Center Requests</title>
<link rel="stylesheet" href="rescue_new.css">
</head>

<body>

<div class="main">
<div class="content-area">

<h2>Rescue Center Requests</h2>

<?php if ($result->num_rows > 0): ?>
<table class="rescue-table">
<thead>
<tr>
    <th>Center Name</th>
    <th>Address</th>
    <th>District</th>
    <th>Contact</th>
    <th>Email</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['center_name']) ?></td>
    <td><?= htmlspecialchars($row['address']) ?></td>
    <td><?= htmlspecialchars($row['district']) ?></td>
    <td><?= htmlspecialchars($row['contact_number']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td>
        <a href="?action=approve&id=<?= $row['rescue_center_id'] ?>" class="approve-btn">Approve</a>
        <a href="?action=reject&id=<?= $row['rescue_center_id'] ?>" class="reject-btn">Reject</a>
    </td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
<?php else: ?>
<div class="no-requests">No pending rescue center requests</div>
<?php endif; ?>

</div>
</div>
<script>
   
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');

    if (msg) {
        let text = '';
        if (msg === 'active') {
            text = 'Rescue center approved successfully!';
        } else if (msg === 'rejected') {
            text = 'Rescue center rejected.';
        }

        if (text) {
            alert(text); 
        }

      
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>
</body>
</html>