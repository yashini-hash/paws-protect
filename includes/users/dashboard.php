<?php
include("sidebar.php");
include("../page/dbconnect.php");

$user_id = $_SESSION['user_id'];

// Fetch user info
$sql = "SELECT name, profile_image FROM users WHERE user_id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
$profile_img = !empty($user['profile_image'])
    ? "../uploads/profiles/" . $user['profile_image']
    : "../uploads/profiles/default.png";

// Fetch Donation Summary
$donation_sql = "SELECT rescue_center, amount, payment_status, donated_at
                 FROM donations
                 WHERE user_id='$user_id'
                 ORDER BY donated_at DESC LIMIT 3"; // only latest 3
$donation_result = mysqli_query($conn, $donation_sql);

// Fetch total donations
$total_donations_sql = "SELECT COUNT(*) as count, SUM(amount) as total FROM donations WHERE user_id='$user_id'";
$total_donations = mysqli_fetch_assoc(mysqli_query($conn, $total_donations_sql));

// Fetch Adoption Summary
$adoption_sql = "SELECT a.animal_id, a.name AS animal_name, a.animal_image AS animal_image, r.status 
                 FROM adopt_requests r
                 JOIN animals_details a ON r.animal_id = a.animal_id
                 WHERE r.user_id='$user_id'
                 ORDER BY r.request_date DESC LIMIT 3";
$adoption_result = mysqli_query($conn, $adoption_sql);

// Fetch total adoptions
$total_adoptions_sql = "SELECT COUNT(*) as count FROM adopt_requests WHERE user_id='$user_id'";
$total_adoptions = mysqli_fetch_assoc(mysqli_query($conn, $total_adoptions_sql));

// Fetch Lost Animals Summary
$lost_sql = "SELECT animal_type, breed, image, status 
             FROM lost_animals 
             WHERE user_id='$user_id'
             ORDER BY created_at DESC LIMIT 3";
$lost_result = mysqli_query($conn, $lost_sql);

// Fetch total lost animals
$total_lost_sql = "SELECT COUNT(*) as count FROM lost_animals WHERE user_id='$user_id'";
$total_lost = mysqli_fetch_assoc(mysqli_query($conn, $total_lost_sql));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #fef6ee;
            margin-left: 280px;
            padding: 30px;
            color: #4b3e2b;
        }

        /* Welcome Section */
        .welcome-box {
            background: linear-gradient(135deg, #FFD8B4, #E6B48A);
            padding: 25px 30px;
            border-radius: 20px;
            color: #5C3A21;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 35px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .welcome-box img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; }
        .welcome-box h2 { font-size: 24px; margin-bottom: 5px; }
        .welcome-box p { font-size: 16px; opacity: 0.85; }

        /* Cards Container */
        .cards { display: flex; gap: 20px; margin-bottom: 40px; flex-wrap: wrap; }
        .card {
            background: #fff;
            padding: 20px;
            flex: 1 1 250px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.12); }
        .card h4 { font-size: 16px; opacity: 0.7; margin-bottom: 8px; }
        .card p { font-size: 22px; font-weight: 600; color: #5C3A21; }

        /* Mini Table inside card */
        .mini-table { margin-top: 10px; }
        .mini-table tr td { padding: 5px 0; }
        img.animal-img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #ddd; margin-right: 8px; vertical-align: middle; }

    </style>
</head>
<body>

<div class="welcome-box">
    <img src="<?php echo $profile_img; ?>" alt="Profile">
    <div>
        <h2>Hi, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
        <p>Welcome back to <strong>Paws & Protect</strong>.</p>
    </div>
</div>

<div class="cards">

    <!-- Donations Card -->
    <div class="card">
        <h4>Total Donations</h4>
        <p>LKR<?php echo number_format($total_donations['total'] ?? 0, 2); ?></p>
        <p>(<?php echo $total_donations['count'] ?? 0; ?> donations)</p>

        <table class="mini-table">
            <?php while($donation = mysqli_fetch_assoc($donation_result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($donation['rescue_center']); ?></td>
                <td>LKR<?php echo number_format($donation['amount'], 2); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Adoptions Card -->
    <div class="card">
        <h4>Total Adoptions</h4>
        <p><?php echo $total_adoptions['count'] ?? 0; ?> animals</p>

        <table class="mini-table">
            <?php while($adoption = mysqli_fetch_assoc($adoption_result)) { ?>
            <tr>
                <td><img class="animal-img" src="<?php echo !empty($adoption['animal_image']) ? '../uploads/addanimal/'.$adoption['animal_image'] : '../uploads/animals/default.png'; ?>" alt="Animal"></td>
                <td><?php echo htmlspecialchars($adoption['animal_name']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Lost Animals Card -->
    <div class="card">
        <h4>Lost Animals</h4>
        <p><?php echo $total_lost['count'] ?? 0; ?> reported</p>

        <table class="mini-table">
            <?php while($lost = mysqli_fetch_assoc($lost_result)) { ?>
            <tr>
                <td><img class="animal-img" src="<?php echo !empty($lost['image']) ? '../uploads/lost/'.$lost['image'] : '../uploads/lost/default.png'; ?>" alt="Animal"></td>
                <td><?php echo htmlspecialchars($lost['animal_type']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>
