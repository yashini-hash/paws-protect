<?php
session_start();
include('auth.php');
include('../page/dbconnect.php');
include('sidebar.php');

$query = "SELECT * FROM users WHERE role='user' ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link rel="stylesheet" href="users.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>

<div class="main">
    <div class="users-container">

        <div class="users-header">
            <h2>All Users</h2>
            <input type="text" id="searchInput" placeholder="Search by name or phone...">
        </div>

        <?php if ($result->num_rows > 0): ?>
        <table class="users-table" id="usersTable">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Created</th>
                </tr>
            </thead>
           <tbody>
<?php while ($u = $result->fetch_assoc()): ?>

<tr>
    <td data-label="Profile">
        <?php
        $imgPath = '../uploads/profiles/';

        $serverPath = __DIR__ . '/../uploads/profiles/';

        $defaultImg = $imgPath . 'default.png';

        if (!empty($u['profile_image']) && file_exists($serverPath . $u['profile_image'])) {
            echo '<img src="' . $imgPath . htmlspecialchars($u['profile_image']) . '" class="profile">';
        } else {
            echo '<img src="' . $defaultImg . '" class="profile">';
        }
        ?>
    </td>

    <td data-label="Name"><?= htmlspecialchars($u['name']); ?></td>
    <td data-label="Phone"><?= htmlspecialchars($u['phone']); ?></td>
    <td data-label="Email"><?= htmlspecialchars($u['email']); ?></td>
    <td data-label="Created"><?= date('d M Y', strtotime($u['created_at'])); ?></td>
</tr>

<?php endwhile; ?>
</tbody>

        </table>
        <?php else: ?>
            <div class="no-users">No users found</div>
        <?php endif; ?>

    </div>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#usersTable tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});
</script>

</body>
</html>
