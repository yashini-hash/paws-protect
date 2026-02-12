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

$rescue_center_id = $_SESSION['rescue_center_id'];
$request_id = $_GET['request_id'] ?? 0;

if ($request_id == 0) {
    echo "<script>alert('Invalid request'); window.location='adoption.php';</script>";
    exit;
}

$sql = "
    SELECT 
        ar.request_id, ar.status, ar.request_date,
        ar.user_id,
        u.name AS user_name, u.email AS user_email, u.phone,
        a.animal_id, a.name AS animal_name, a.type, a.breed,
        a.gender, a.age, a.health, a.vaccination, a.animal_image,
        a.location, a.details
    FROM adopt_requests ar
    INNER JOIN users u ON ar.user_id = u.user_id
    INNER JOIN animals_details a ON ar.animal_id = a.animal_id
    WHERE ar.request_id = ? AND ar.rescue_center_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $request_id, $rescue_center_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<script>alert('Request not found'); window.location='adoption.php';</script>";
    exit;
}

$historySql = "
    SELECT a.name, a.type, a.breed, a.age, a.animal_image, ar.status, ar.request_date
    FROM adopt_requests ar
    INNER JOIN animals_details a ON ar.animal_id = a.animal_id
    WHERE ar.user_id = ? AND ar.request_id != ?
    ORDER BY ar.request_date DESC
";
$historyStmt = $conn->prepare($historySql);
$historyStmt->bind_param("ii", $data['user_id'], $request_id);
$historyStmt->execute();
$adoptionHistory = $historyStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Adoption Request Details</title>
<link rel="stylesheet" href="adoption_request_view.css">
</head>
<body>

<div class="main-container">
    <div class="details-card">

        <h1 style="text-align:center; color:#4b2e1e; margin-bottom:25px;">
            Adoption Request Details
        </h1>

        <div class="two-column">

            <div class="left-box">
                <img src="../uploads/addanimal/<?= htmlspecialchars($data['animal_image']) ?>" class="animal-img-big" alt="Animal Image">
            </div>

            <div class="right-box">

                <div class="section-title">Animal Details</div>

                <div class="info-box"><strong>Name:</strong> <?= htmlspecialchars($data['animal_name']) ?></div>
                <div class="info-box"><strong>Type:</strong> <?= htmlspecialchars($data['type']) ?></div>
                <div class="info-box"><strong>Gender:</strong> <?= htmlspecialchars($data['gender']) ?></div>
                <div class="info-box"><strong>Age:</strong> <?= htmlspecialchars($data['age']) ?></div>
                <div class="info-box"><strong>Health:</strong> <?= htmlspecialchars($data['health']) ?></div>
                <div class="info-box"><strong>Vaccination:</strong> <?= htmlspecialchars($data['vaccination']) ?></div>
                <div class="info-box"><strong>Location:</strong> <?= htmlspecialchars($data['location']) ?></div>

                <div class="section-title">User Details</div>

                <div class="info-box"><strong>Name:</strong> <?= htmlspecialchars($data['user_name']) ?></div>
                <div class="info-box"><strong>Email:</strong> <?= htmlspecialchars($data['user_email']) ?></div>
                <div class="info-box"><strong>Phone:</strong> <?= htmlspecialchars($data['phone']) ?></div>

                <div class="section-title">User Adoption History</div>

<?php if($adoptionHistory->num_rows > 0): ?>
    <div class="prev-adoptions">
        <?php while($animal = $adoptionHistory->fetch_assoc()): ?>
            <div class="prev-card">
                <img src="../uploads/addanimal/<?= htmlspecialchars($animal['animal_image']) ?>" alt="Animal Image">
                <div>
                    <strong>Name:</strong> <?= htmlspecialchars($animal['name']) ?><br>
                    <strong>Type:</strong> <?= htmlspecialchars($animal['type']) ?><br>
                    <strong>Age:</strong> <?= htmlspecialchars($animal['age']) ?><br>
                    <strong>Status:</strong> <?= htmlspecialchars($animal['status']) ?><br>
                    <strong>Requested On:</strong> <?= htmlspecialchars($animal['request_date']) ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="info-box">No adoption history found.</div>
<?php endif; ?>

            </div>

        </div>

        <div class="button-group">
            <button class="action-btn btn-approve" onclick="handleRequest(<?= $data['request_id'] ?>,'approve')">Approve</button>
            <button class="action-btn btn-reject" onclick="handleRequest(<?= $data['request_id'] ?>,'reject')">Reject</button>
            <a href="adoption.php"><button class="action-btn btn-back">Back</button></a>
        </div>

    </div>
</div>

<div id="msg-box" style="
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #28a745;
    color: white;
    padding: 15px 25px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    display: none;
    z-index: 9999;
    font-weight: 500;
    font-size: 16px;
    text-align: center;
    cursor: pointer;
"></div>

<script>
function handleRequest(requestId, action) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "adoption_action.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if(this.status == 200) {
            showMessage(this.responseText, action);
        }
    };
    xhr.send("request_id="+requestId+"&action="+action);
}

function showMessage(msg, action) {
    let box = document.getElementById("msg-box");
    box.innerText = msg;
    box.style.background = (action == 'approve') ? "#28a745" : "#dc3545";
    box.style.display = "block";
    box.style.opacity = 1;

    
    box.onclick = () => {
        box.style.display = "none";
    };
}
</script>


</body>
</html>
