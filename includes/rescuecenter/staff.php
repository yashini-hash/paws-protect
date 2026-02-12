<?php
session_start();
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

$rescue_center_id = (int) $_SESSION['rescue_center_id'];

$error = '';
$success = '';

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
<link rel="stylesheet" href="staff.css">

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
