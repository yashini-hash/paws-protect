<?php
include("sidebar.php");
include("../page/dbconnect.php");

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

$rescue_id = (int)$_SESSION['rescue_center_id'];
$center_name = "";

$stmt = $conn->prepare("SELECT center_name FROM rescue_center WHERE rescue_center_id = ?");
$stmt->bind_param("i", $rescue_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $center_name = $row['center_name'];
}

$total_sql = "
    SELECT IFNULL(SUM(amount),0) AS total_amount
    FROM donations
    WHERE rescue_center = ?
      AND payment_status = 'Success'
      AND MONTH(donated_at) = MONTH(CURRENT_DATE())
      AND YEAR(donated_at) = YEAR(CURRENT_DATE())
";

$stmt = $conn->prepare($total_sql);
$stmt->bind_param("s", $center_name);
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_donations = $total_row['total_amount'];

$list_sql = "
    SELECT donor_name, donor_email, phone, amount, payment_method,
           payment_status, transaction_ref, donated_at
    FROM donations
    WHERE rescue_center = ?
    ORDER BY donated_at DESC
";

$stmt = $conn->prepare($list_sql);
$stmt->bind_param("s", $center_name);
$stmt->execute();
$donations = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donation Details</title>
    <link rel="stylesheet" href="donation.css">
   
</head>
<body>

<h2>Donations Received</h2>

<div class="summary-box">
    Total Donations This Month<br>
    <span>LKR <?= number_format($total_donations, 2) ?></span>
</div>

<?php if ($donations->num_rows > 0): ?>
<table>
    <tr>
        <th>Donor</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Amount (LKR)</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Transaction</th>
        <th>Date</th>
    </tr>

    <?php while ($row = $donations->fetch_assoc()): ?>
    <tr>
       <td data-label="Donor"><?= htmlspecialchars($row['donor_name'] ?? 'Anonymous') ?></td>
<td data-label="Email"><?= htmlspecialchars($row['donor_email'] ?? '-') ?></td>
<td data-label="Phone"><?= htmlspecialchars($row['phone']) ?></td>
<td data-label="Amount"><?= number_format($row['amount'], 2) ?></td>
<td data-label="Payment"><?= htmlspecialchars($row['payment_method']) ?></td>
<td data-label="Status" class="<?= strtolower($row['payment_status']) ?>">
    <?= htmlspecialchars($row['payment_status']) ?>
</td>
<td data-label="Transaction"><?= htmlspecialchars($row['transaction_ref'] ?? '-') ?></td>
<td data-label="Date"><?= date("d M Y, h:i A", strtotime($row['donated_at'])) ?></td>

    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <div class="no-data">No donations found.</div>
<?php endif; ?>

</body>
</html>
