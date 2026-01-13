<?php


include("sidebar.php");
include("../page/dbconnect.php");

// Security check
if (empty($_SESSION['rescue_center_id'])) {
    header("Location: login.php");
    exit();
}

// Get rescue center name
$rescue_id = (int)$_SESSION['rescue_center_id'];
$center_name = "";

$stmt = $conn->prepare("SELECT center_name FROM rescue_center WHERE rescue_center_id = ?");
$stmt->bind_param("i", $rescue_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $center_name = $row['center_name'];
}

// ---------------- TOTAL DONATIONS (THIS MONTH) ----------------
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

// ---------------- FETCH DONATION DETAILS ----------------
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
    <style>
        body {
             background: #FFF8E7;
            font-family: Arial, sans-serif;
            padding: 25px;
        }

        h2 {
            text-align: center;
            color: #5a3e1b;
            margin-bottom: 10px;
        }

        .summary-box {
            max-width: 400px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            font-size: 18px;
            font-weight: bold;
        }

        .summary-box span {
            color: green;
            font-size: 22px;
        }

        table {
        margin-left: 260px;
            width: 80%;
            border-collapse: collapse;
            background: #ffffff;
            margin-top: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #ddbc8b;
            color: #333;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .success { color: green; font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
        .failed  { color: red; font-weight: bold; }

        .no-data {
            text-align: center;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            margin-top: 30px;
            color: #777;
        }
    </style>
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
        <td><?= htmlspecialchars($row['donor_name'] ?? 'Anonymous') ?></td>
        <td><?= htmlspecialchars($row['donor_email'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= number_format($row['amount'], 2) ?></td>
        <td><?= htmlspecialchars($row['payment_method']) ?></td>
        <td class="<?= strtolower($row['payment_status']) ?>">
            <?= htmlspecialchars($row['payment_status']) ?>
        </td>
        <td><?= htmlspecialchars($row['transaction_ref'] ?? '-') ?></td>
        <td><?= date("d M Y, h:i A", strtotime($row['donated_at'])) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <div class="no-data">No donations found.</div>
<?php endif; ?>

</body>
</html>
