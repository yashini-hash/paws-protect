<?php
session_start();
include("sidebar.php"); 
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    exit("<p style='color:red;text-align:center;'>Unauthorized Access</p>");
}

$rescue_center_id = $_SESSION['rescue_center_id'];

/* ===== DELETE ANIMAL ===== */
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    $imgQ = $conn->prepare("SELECT animal_image FROM animals_details WHERE animal_id=? AND rescue_center_id=?");
    $imgQ->bind_param("ii",$id,$rescue_center_id);
    $imgQ->execute();
    $imgRes = $imgQ->get_result()->fetch_assoc();
    if(!empty($imgRes['animal_image'])) unlink("../uploads/".$imgRes['animal_image']);

    $stmt = $conn->prepare("DELETE FROM animals_details WHERE animal_id=? AND rescue_center_id=?");
    $stmt->bind_param("ii",$id,$rescue_center_id);
    exit($stmt->execute() ? "deleted" : "error");
}

/* ===== FETCH ANIMALS ===== */
$type_filter = $_GET['type_filter'] ?? '';
if($type_filter && $type_filter != "all"){
    $stmt = $conn->prepare("SELECT * FROM animals_details WHERE rescue_center_id=? AND type=? ORDER BY animal_id DESC");
    $stmt->bind_param("is",$rescue_center_id,$type_filter);
}else{
    $stmt = $conn->prepare("SELECT * FROM animals_details WHERE rescue_center_id=? ORDER BY animal_id DESC");
    $stmt->bind_param("i",$rescue_center_id);
}
$stmt->execute();
$result = $stmt->get_result();
$types = ['Dog','Cat','Bird','Rabbit','Hamsters'];
?>

<h2 style="text-align:center;color:#5C3A21;padding:20px;">My Animals</h2>

<form id="filterForm" style="display:flex;justify-content:center;gap:10px;margin-bottom:20px;">
    <select id="type_filter">
        <option value="all">All Types</option>
        <?php foreach($types as $t): ?>
            <option value="<?= $t ?>" <?= $type_filter==$t?"selected":"" ?>><?= $t ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<div id="animalsContainer" style="display:flex;flex-wrap:wrap;gap:20px;justify-content:center;">
<?php if($result->num_rows>0): ?>
    <?php while($a=$result->fetch_assoc()): ?>
        <div class="animal-card" data-id="<?= $a['animal_id'] ?>" style="background:#ddbc8b;padding:10px;border-radius:15px;width:280px;text-align:center;">
            <img src="../uploads/<?= $a['animal_image']?:'no-image.png' ?>" style="width:220px;height:220px;object-fit:cover;border-radius:10px;margin-bottom:10px;">
            <h3><?= htmlspecialchars($a['name']) ?></h3>
            <p><b>Type:</b> <?= $a['type'] ?></p>
            <p><b>Age:</b> <?= $a['age'] ?></p>
            <p><b>Health:</b> <?= $a['health'] ?></p>
            <p><b>Status:</b> <?= ucwords(str_replace("_"," ",$a['adoption_status'])) ?></p>
            <div style="margin-top:10px;">
                <a href="change_animal.php?id=<?= $a['animal_id'] ?>" style="background:#5C3A21;color:white;padding:5px 10px;border:none;border-radius:5px;text-decoration:none;">Update</a>
                <button class="deleteBtn" style="background:red;color:white;padding:5px 10px;border:none;border-radius:5px;">Delete</button>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <h3 style="text-align:center;color:red;">❌ Animal Not Found</h3>
<?php endif; ?>
</div>

<script>
// Delete Animal using Event Delegation
document.getElementById('animalsContainer').addEventListener('click', function(e){
    const btn = e.target.closest('.deleteBtn'); // robust
    if(!btn) return; // only trigger on delete button

    const card = btn.closest('.animal-card');
    if(!card) return;

    if(!confirm("Delete this animal?")) return;

    fetch('delete_animal.php?delete_id=' + card.dataset.id)
    .then(res => res.text())
    .then(data => {
        data = data.trim();
        if(data === "deleted"){
            card.remove(); // remove card from DOM
            alert("Deleted successfully!");
        } else {
            alert("Delete failed: " + data);
        }
    })
    .catch(err => alert("Error connecting to server"));
});

// Filter animals
document.getElementById('filterForm').addEventListener('submit', e => {
    e.preventDefault();
    const type = document.getElementById('type_filter').value;
    window.location.href = "?type_filter=" + type;
});
</script>


<style>
body { background:#FFF8E7; font-family: Arial, sans-serif; margin-left: 260px; padding: 50px; }
    #filterForm {
         max-width:600px; margin:auto; margin-bottom:20px; display:flex; gap:10px; } #filterForm select { flex:1; padding:8px; border-radius:5px; border:1px solid #ccc; } #filterForm button { padding:8px 15px; border:none; border-radius:5px; background:#5C3A21; color:white; cursor:pointer; } #filterForm button:hover { background:#9d6e4c; } #animalsContainer { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; }

.animal-card h3 { text-align: center; margin: 6px 0; color: #3e2c1c; font-size: 25px; } .animal-card p { margin: 4px 0; color: #5c3a21; font-size: 20x; }
</style>