<?php
include("auth.php");
include("../page/dbconnect.php");   
include("sidebar.php");           
$where = [];


if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $where[] = "DATE(donated_at) BETWEEN '$from' AND '$to'";
}

if (!empty($_GET['min_amount'])) {
    $min = $_GET['min_amount'];
    $where[] = "amount >= '$min'";
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$query = "SELECT * FROM donations $whereSQL ORDER BY donated_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donations | Admin</title>
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="donations.css">
</head>

<body>

<div class="page-content">

<h2><i class="fa-solid fa-hand-holding-dollar"></i>  Donations</h2>

<div class="filter-box">
    <form method="GET">
        From:
        <input type="date" name="from_date" value="<?= $_GET['from_date'] ?? '' ?>">

        To:
        <input type="date" name="to_date" value="<?= $_GET['to_date'] ?? '' ?>">

        Min Amount:
        <input type="number" name="min_amount"
               value="<?= $_GET['min_amount'] ?? '' ?>"
               placeholder="Rs.">

        <button type="submit" class="btn">Filter</button>
    </form>
</div>


<table>
    <tr>
        <th>Donor Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Rescue Center</th>
        <th>Amount (Rs)</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

    <?php if(mysqli_num_rows($result) > 0) { ?>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['donor_name']; ?></td>
                <td><?= $row['donor_email']; ?></td>
                <td><?= $row['phone']; ?></td>
                <td><?= $row['rescue_center']; ?></td>
                <td><?= number_format($row['amount'], 2); ?></td>
                <td><?= $row['payment_method']; ?></td>

                <td class="
                    <?php
                    if ($row['payment_status'] == 'Success') echo 'status-success';
                    elseif ($row['payment_status'] == 'Pending') echo 'status-pending';
                    else echo 'status-failed';
                    ?>">
                    <?= $row['payment_status']; ?>
                </td>

                <td><?= date("Y-m-d", strtotime($row['donated_at'])); ?></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="9">No donation records found</td>
        </tr>
    <?php } ?>
</table>

</body>
</html>                            