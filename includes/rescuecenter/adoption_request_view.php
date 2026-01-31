<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
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
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body {
    background: #f4f1ed;
}

.main-container {
    margin-left: 260px; 
    padding: 40px;
}

.details-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    padding: 35px;
    max-width: 1500px;
    margin: auto;
    transition: transform 0.3s ease;
}
.details-card:hover {
    transform: translateY(-5px);
}

.section-title {
    font-size: 22px;
    font-weight: 600;
    color: #4b2e1e;
    margin: 25px 0 15px 0;
    border-bottom: 2px solid #f2d6b3;
    padding-bottom: 5px;
}

.info-box {
    background: #fef8f2;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 12px;
    font-size: 16px;
    color: #333;
}

.two-column {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
}

.left-box {
    flex: 1 1 350px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.animal-img-big {
    margin-top:50px;
    width: 100%;
    max-width: 460px;
    height: 600px;
    object-fit: cover;
    border-radius: 20px;
    border: 5px solid #e6c9a8;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.right-box {
    flex: 2 1 300px;
}

.button-group {
    text-align: center;
    margin-top: 30px;
}

.action-btn {
    padding: 12px 25px;
    border-radius: 12px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    margin: 0 10px;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-approve { background: #28a745; }
.btn-approve:hover { background: #218838; transform: scale(1.05); }
.btn-reject { background: #dc3545; }
.btn-reject:hover { background: #c82333; transform: scale(1.05); }
.btn-back { background: #6c4f3d; }
.btn-back:hover { background: #5c4033; transform: scale(1.05); }

.prev-adoptions {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 15px;
}
.prev-card {
    background: #fef8f2;
    border-radius: 12px;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1 1 250px;
}
.prev-card img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #e6c9a8;
}

@media (max-width: 1024px) {
    .main-container {
        margin-left: 0;
        padding: 25px;
    }

    .two-column {
        flex-direction: column;
        align-items: center;
    }

    .animal-img-big {
        height: 500px;
    }

    .right-box {
        width: 100%;
    }
}

@media (max-width: 600px) {

    .animal-img-big {
        height: 360px;
    }

    .button-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .action-btn {
        width: 100%;
        font-size: 15px;
    }

    .prev-card {
        flex-direction: column;
        text-align: center;
    }

    .prev-card img {
        width: 200px;
        height: 200px;
    }
}

</style>
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
    box.style.opacity = 1;
    box.style.display = "block";

    let fadeOut = setInterval(() => {
        if (box.style.opacity > 0) {
            box.style.opacity -= 0.05;
        } else {
            box.style.display = "none";
            clearInterval(fadeOut);
        }
    }, 3000s);
}
</script>

</body>
</html>
