<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

// Check if rescue center is logged in
if (!isset($_SESSION['rescue_center_id'])) {
    die("❌ Rescue center not logged in.");
}

$rescue_center_id = (int) $_SESSION['rescue_center_id'];

$error = '';
$success = '';

/* -------------------- HANDLE ADD STAFF -------------------- */
if (isset($_POST['add_staff'])) {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($name)) {
        $error = "Staff name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO staff (rescue_center_id, name, email, phone, status) VALUES (?, ?, ?, ?, 'active')");
        $stmt->bind_param("isss", $rescue_center_id, $name, $email, $phone);
        if ($stmt->execute()) {
            $success = "✅ Staff member added successfully!";
        } else {
            $error = "❌ Error adding staff: " . $conn->error;
        }
        $stmt->close();
    }
}

/* -------------------- HANDLE DELETE STAFF -------------------- */
if (isset($_GET['delete'])) {
    $staff_id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ? AND rescue_center_id = ?");
    $stmt->bind_param("ii", $staff_id, $rescue_center_id);
    if ($stmt->execute()) {
        $success = "✅ Staff deleted successfully!";
    } else {
        $error = "❌ Error deleting staff: " . $conn->error;
    }
    $stmt->close();
}

/* -------------------- HANDLE TOGGLE STATUS -------------------- */
if (isset($_GET['toggle'])) {
    $staff_id = (int) $_GET['toggle'];
    $current = $_GET['status'] === 'active' ? 'inactive' : 'active';
    $stmt = $conn->prepare("UPDATE staff SET status = ? WHERE staff_id = ? AND rescue_center_id = ?");
    $stmt->bind_param("sii", $current, $staff_id, $rescue_center_id);
    if ($stmt->execute()) {
        $success = "✅ Staff status updated to $current!";
    } else {
        $error = "❌ Error updating status: " . $conn->error;
    }
    $stmt->close();
}

/* -------------------- HANDLE EDIT STAFF -------------------- */
if (isset($_POST['edit_staff'])) {
    $staff_id = (int) $_POST['staff_id'];
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);

    if (empty($name)) {
        $error = "Staff name is required.";
    } else {
        $stmt = $conn->prepare("UPDATE staff SET name = ?, email = ?, phone = ? WHERE staff_id = ? AND rescue_center_id = ?");
        $stmt->bind_param("sssii", $name, $email, $phone, $staff_id, $rescue_center_id);
        if ($stmt->execute()) {
            $success = "✅ Staff details updated!";
        } else {
            $error = "❌ Error updating staff: " . $conn->error;
        }
        $stmt->close();
    }
}

/* -------------------- FETCH ALL STAFF -------------------- */
$staffResult = $conn->prepare("SELECT * FROM staff WHERE rescue_center_id = ? ORDER BY staff_id DESC");
$staffResult->bind_param("i", $rescue_center_id);
$staffResult->execute();
$staffData = $staffResult->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Staff | Rescue Center</title>
<style>
body { font-family: Arial,sans-serif; background:#FFF8E7; margin-left:120px; padding:40px; }
.container { max-width: 900px; margin:auto; background:white; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.2); }
h2 { text-align:center; margin-bottom:20px; }

/* Messages */
.success { background:#d4edda; color:#155724; padding:10px; border-radius:6px; text-align:center; margin-bottom:10px; }
.error { background:#f8d7da; color:#721c24; padding:10px; border-radius:6px; text-align:center; margin-bottom:10px; }

/* Form */
input { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
button { padding:10px 15px; border:none; border-radius:6px; background:#5C3A21; color:white; cursor:pointer; margin-top:5px; }
button:hover { background:#9d6e4c; }

/* Table */
table { width:100%; border-collapse:collapse; margin-top:20px; }
th,td { border:1px solid #ddd; padding:10px; text-align:left; }
th { background:#5C3A21; color:white; }
.status { padding:5px 12px; border-radius:12px; font-weight:bold; }
.status.active { background:#d4edda; color:#155724; }
.status.inactive { background:#f8d7da; color:#721c24; }
a { text-decoration:none; margin-right:5px; }
</style>
</head>
<body>

<div class="container">
    <h2> Add Staff Member</h2>

    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="staff_id" id="staff_id">
        <input type="text" name="name" id="name" placeholder="Staff Name" required>
        <input type="email" name="email" id="email" placeholder="Staff Email (optional)">
        <input type="text" name="phone" id="phone" placeholder="Phone Number (optional)">
        <button type="submit" name="add_staff" id="submit_btn">Add Staff</button>
    </form>

    <h2>👥 Staff List</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php $i=1; while($staff = $staffData->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($staff['name']) ?></td>
            <td><?= htmlspecialchars($staff['email']) ?></td>
            <td><?= htmlspecialchars($staff['phone']) ?></td>
            <td><span class="status <?= $staff['status'] ?>"><?= ucfirst($staff['status']) ?></span></td>
            <td>
                <a href="#" onclick="editStaff(<?= $staff['staff_id'] ?>,'<?= htmlspecialchars(addslashes($staff['name'])) ?>','<?= htmlspecialchars(addslashes($staff['email'])) ?>','<?= htmlspecialchars(addslashes($staff['phone'])) ?>')">Edit</a>
                <a href="?delete=<?= $staff['staff_id'] ?>" onclick="return confirm('Are you sure to delete?')">Delete</a>
                <a href="?toggle=<?= $staff['staff_id'] ?>&status=<?= $staff['status'] ?>">
                    <?= $staff['status']=='active'?'Deactivate':'Activate' ?>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
function editStaff(id, name, email, phone) {
    document.getElementById('staff_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('email').value = email;
    document.getElementById('phone').value = phone;
    document.getElementById('submit_btn').name = 'edit_staff';
    document.getElementById('submit_btn').textContent = 'Update Staff';
}
</script>

</body>
</html>
