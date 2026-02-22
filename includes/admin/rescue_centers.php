<?php
include('auth.php');
include('../page/dbconnect.php');
include('sidebar.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

$status_for_popup = ''; 

if (isset($_POST['update_status'])) {
    $center_id = intval($_POST['center_id']);
    $new_status = $_POST['new_status'];

  
    $stmt = $conn->prepare("SELECT center_name, email FROM rescue_center WHERE rescue_center_id=?");
    $stmt->bind_param("i", $center_id);
    $stmt->execute();
    $center = $stmt->get_result()->fetch_assoc();
    $stmt->close();

   
    $stmt = $conn->prepare("UPDATE rescue_center SET status=? WHERE rescue_center_id=?");
    $stmt->bind_param("si", $new_status, $center_id);
    if ($stmt->execute()) {
        $message = "Status updated successfully!";
        $status_for_popup = $new_status;
    } else {
        $error = "Failed to update status!";
    }
    $stmt->close();

 
    if ($center) {
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
            $mail->Subject = 'Registration Update for Paws & Protect';

            if ($new_status === 'active') {
                $mail->Body = "
                    <h3>Registration Update</h3>
                  <p>Dear <b>{$center['center_name']}</b>,</p>
                 <p>We are pleased to inform you that your rescue center registration has been <strong>approved and activated</strong>. Your account is now fully active, and you can log in to the system to manage your center and begin assisting animals.</p>
                  <p>If you have any questions or need assistance, please do not hesitate to contact our support team.</p>
                    <br>
                   <p>Best regards,<br>The Paws & Protect Team</p>
                ";
            } elseif ($new_status === 'inactive') {
                $mail->Body = "
                    <h3>Registration Update</h3>
                   <p>Dear <b>{$center['center_name']}</b>,</p>
                    <p>We would like to inform you that your rescue center account has been <strong> deactivated</strong>. During this period, access to the system will be restricted.</p>
                      <p>If you have any questions or require further assistance, please do not hesitate to contact our support team.</p>
                    <br>
                      <p>Best regards,<br>The Paws & Protect Team</p>
                ";
            } elseif ($new_status === 'rejected') {
                $mail->Body = "
                    <h3>Registration Update</h3>
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

$statuses = ['active', 'inactive', 'rejected'];
$rescue_centers = [];

foreach ($statuses as $status) {
    $stmt = $conn->prepare(
        "SELECT rescue_center_id, center_name, district, contact_number, email 
         FROM rescue_center 
         WHERE status=? 
         ORDER BY rescue_center_id DESC"
    );
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $rescue_centers[$status] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rescue Centers</title>
<link rel="stylesheet" href="rescue_centers.css">
<style>
</style>
</head>
<body>

<div class="main">
<div class="page-card">

<?php if(isset($message)) echo "<p style='color:green;font-weight:bold'>$message</p>"; ?>
<?php if(isset($error)) echo "<p style='color:red;font-weight:bold'>$error</p>"; ?>

<div class="page-header">
    <h2>Rescue Centers</h2>
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search by name, district or phone">
    </div>
</div>

<?php foreach ($statuses as $status): ?>
<button type="button" class="collapsible"><?= ucfirst($status) ?> Rescue Centers (<?= count($rescue_centers[$status]) ?>)</button>
<div class="content">
    <table class="rescue-table" id="table_<?= $status ?>">
        <thead>
            <tr>
                <th>Name</th>
                <th>District</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($rescue_centers[$status])): ?>
            <?php foreach ($rescue_centers[$status] as $row): ?>
            <tr>
                <td data-label="Name"><?= htmlspecialchars($row['center_name']) ?></td>
                <td data-label="District"><?= htmlspecialchars($row['district']) ?></td>
                <td data-label="Contact"><?= htmlspecialchars($row['contact_number']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                <td data-label="Action">
                    <form method="POST" style="margin:0;">
                        <input type="hidden" name="center_id" value="<?= $row['rescue_center_id'] ?>">
                        <?php if($status === 'active'): ?>
                            <input type="hidden" name="new_status" value="inactive">
                            <button type="submit" name="update_status" class="status-btn btn-inactive">Deactivate</button>
                        <?php elseif($status === 'inactive'): ?>
                            <input type="hidden" name="new_status" value="active">
                            <button type="submit" name="update_status" class="status-btn btn-active">Activate</button>
                        <?php elseif($status === 'rejected'): ?>
                            <input type="hidden" name="new_status" value="active">
                            <button type="submit" name="update_status" class="status-btn btn-approve">Approve</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="no-data">No <?= ucfirst($status) ?> rescue centers found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php endforeach; ?>

</div>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    <?php foreach ($statuses as $status): ?>
    document.querySelectorAll("#table_<?= $status ?> tbody tr").forEach(row => {
        row.style.display =
            row.textContent.toLowerCase().includes(value)
            ? ""
            : "none";
    });
    <?php endforeach; ?>
});

var coll = document.getElementsByClassName("collapsible");
for (let i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        content.style.display = (content.style.display === "block") ? "none" : "block";
    });
}

<?php if($status_for_popup): ?>
let statusMessage = '<?= $status_for_popup ?>';
let text = '';
switch(statusMessage) {
    case 'active':
        text = 'Registration Update – Paws & Protect\n\nThe rescue center has been ACTIVATED!';
        break;
    case 'inactive':
        text = 'Registration Update – Paws & Protect\n\nThe rescue center has been DEACTIVATED.';
        break;
    case 'rejected':
        text = 'Registration Update – Paws & Protect\n\nThe rescue center has been REJECTED.';
        break;
}
if(text) alert(text);
<?php endif; ?>
</script>

</body>
</html>