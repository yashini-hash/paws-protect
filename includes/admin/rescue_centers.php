<?php
include('auth.php');
include('../page/dbconnect.php');
include('sidebar.php');

if (isset($_POST['update_status'])) {
    $center_id = intval($_POST['center_id']);
    $new_status = $_POST['new_status'];

    $stmt = $conn->prepare("UPDATE rescue_center SET status=? WHERE rescue_center_id=?");
    $stmt->bind_param("si", $new_status, $center_id);
    if ($stmt->execute()) {
        $message = "Status updated successfully!";
    } else {
        $error = "Failed to update status!";
    }
    $stmt->close();
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
        if (content.style.display === "block") {
            content.style.display = "none";
        } else {
            content.style.display = "block";
        }
    });
}
</script>

</body>
</html>
