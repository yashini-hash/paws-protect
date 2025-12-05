<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rescue Center Dashboard</title>
    <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paws.png" />
    <link rel="stylesheet" href="dashboard.css">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="rescue-logo.png" alt="Rescue Logo">
            <h3>Rescue Center</h3>
        </div>


        <ul class="menu">
    <li class="has-submenu" id="animalBtn">
        <i class="fa-solid fa-paw"></i> Animal Management

        <ul class="submenu" id="animalSubMenu">
            <li onclick="loadPage('viewall.php')">View All</li>
            <li onclick="loadPage('addanimal.php')">Add New Animal</li>
            <li onclick="loadPage('updateanimal.php')">Update</li>
            <li onclick="loadPage('deleteanimal.php')">Delete</li>
        </ul>
    </li>
    <li id="adoptionBtn"><i class="fa-solid fa-users"></i> Adoption Request</li>
    <li id="rescueBtn"><i class="fa-solid fa-truck-medical"></i> Rescue Operations</li>
    <li id="feedbackBtn"><i class="fa-solid fa-comment-dots"></i> View Feedback</li>
    <li id="lostBtn"><i class="fa-solid fa-dog"></i> Lost Animal Details</li>
    <li id="profileBtn"><i class="fa-solid fa-user"></i> Edit Profile</li>
    <li id="staffBtn"><i class="fa-solid fa-user-tie"></i> Staff</li>

</ul>

    </div>

    <div class="main">
        <div class="topbar">
            <h2>Welcome to Rescue Center Dashboard !</h2>
            <i class="fa-solid fa-bell alert-icon"></i>
        </div>

        <div class="content-area"></div>
        

    <script>
function loadPage(page) {
    fetch("/paws&protect/includes/rescuecenter/" + page)
        .then(response => response.text())
        .then(data => {
            document.querySelector(".content-area").innerHTML = data;
        })
        .catch(error => {
            document.querySelector(".content-area").innerHTML = "<p style='color:red;'>Error loading page.</p>";
        });
}


document.getElementById("animalBtn").addEventListener("click", function (e) {
    e.stopPropagation(); // ✅ prevents conflict
    const subMenu = document.getElementById("animalSubMenu");

    subMenu.style.display = subMenu.style.display === "block" ? "none" : "block";
});

/* ✅ OTHER MAIN BUTTONS AUTO-CLOSE SUBMENU */
document.getElementById("adoptionBtn").onclick = () => {
    document.getElementById("animalSubMenu").style.display = "none";
    loadPage("adoption.php");
};

document.getElementById("rescueBtn").onclick = () => {
    document.getElementById("animalSubMenu").style.display = "none";
    loadPage("rescue.php");
};

document.getElementById("feedbackBtn").onclick = () => {
    document.getElementById("animalSubMenu").style.display = "none";
    loadPage("feedback.php");
};

document.getElementById("lostBtn").onclick = () => {
    document.getElementById("animalSubMenu").style.display = "none";
    loadPage("lost.php");
};

document.getElementById("profileBtn").onclick = () => {
    document.getElementById("animalSubMenu").style.display = "none";
    loadPage("profile.php");
};

document.getElementById("staffBtn").onclick = () => {
    document.getElementById("animalSubMenu").style.display = "none";
    loadPage("staff.php");
};
</script>



</body>
</html>
