<?php
session_start();
include("dbconnect.php");

<<<<<<< HEAD
/* -------------------------------
   VALIDATE INPUT
-------------------------------- */
=======
>>>>>>> 1f2c22c799a2aa0936f3dd3ea0b5b997c764bae1
if (
    empty($_POST['animal_type']) ||
    empty($_POST['description']) ||
    empty($_POST['contact_number']) ||
    empty($_POST['lat']) ||
    empty($_POST['lng'])
) {
    $_SESSION['error_msg'] = "❌ All fields are required.";
    header("Location: rescue.php");
    exit();
}

$animal_type    = trim($_POST['animal_type']);
$description    = trim($_POST['description']);
$contact_number = trim($_POST['contact_number']);
<<<<<<< HEAD
$userLat        = floatval($_POST['lat']);
$userLng        = floatval($_POST['lng']);

$rescue_location = "Lat: $userLat, Lng: $userLng";

/* -------------------------------
   FUNCTION: Calculate Distance
-------------------------------- */
function distance($lat1, $lon1, $lat2, $lon2) {
=======
$userLat        = (float) $_POST['lat'];
$userLng        = (float) $_POST['lng'];

$rescue_location = "Lat: $userLat, Lng: $userLng";

function distance($lat1, $lon1, $lat2, $lon2)
{
>>>>>>> 1f2c22c799a2aa0936f3dd3ea0b5b997c764bae1
    $R = 6371; // Earth radius in KM
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    return $R * (2 * atan2(sqrt($a), sqrt(1 - $a)));
}

$nearestCenterId = null;
$minDistance = PHP_FLOAT_MAX;

$query = "
    SELECT rescue_center_id, latitude, longitude
    FROM rescue_center
    WHERE status = 'active'
      AND latitude IS NOT NULL
      AND longitude IS NOT NULL
";

$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    $_SESSION['error_msg'] = "❌ No active rescue centers available right now.";
    header("Location: rescue.php");
    exit();
}

while ($row = $result->fetch_assoc()) {
    $dist = distance($userLat, $userLng, $row['latitude'], $row['longitude']);
    if ($dist < $minDistance) {
        $minDistance = $dist;
        $nearestCenterId = (int) $row['rescue_center_id'];
    }
}

<<<<<<< HEAD
/* -------------------------------
   INSERT RESCUE REQUEST
-------------------------------- */
=======
>>>>>>> 1f2c22c799a2aa0936f3dd3ea0b5b997c764bae1
$stmt = $conn->prepare("
    INSERT INTO rescue_requests
    (animal_type, rescue_location, description, contact_number, rescue_center_id)
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    $_SESSION['error_msg'] = "❌ Database error. Please try again.";
    header("Location: rescue.php");
    exit();
}

$stmt->bind_param(
    "ssssi",
    $animal_type,
    $rescue_location,
    $description,
    $contact_number,
    $nearestCenterId
);

if ($stmt->execute()) {
    $_SESSION['success_msg'] =
        "🐾 Rescue request submitted successfully. Our team will contact you soon.";
} else {
    $_SESSION['error_msg'] = "❌ Failed to submit rescue request.";
}

$stmt->close();
$conn->close();

header("Location: rescue.php");
exit();
