<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paws & Protect</title>
<link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
<link rel="stylesheet" href="com.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Slider Styles */
.smooth-slider {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 80px;
}

.slider-container {
    width: 800px;
    height: 350px;
    position: relative;
}

.slide {
    position: absolute;
    width: 500px;
    height: 100%;
    border-radius: 20px;
    overflow: hidden;
    background: #000;
    opacity: 0;
    transform: scale(0.7);
    transition: 0.8s ease;
    box-shadow: 0 15px 30px rgba(0,0,0,0.35);
}

.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Main big image */
.slide.active {
    opacity: 1;
    transform: translateX(0) scale(1);
    z-index: 10;
}

/* 1st small card */
.slide.next1 {
    opacity: 1;
    width: 250px;
    transform: translateX(260px) scale(0.85);
    z-index: 8;
}

/* 2nd small card */
.slide.next2 {
    opacity: 1;
    width: 220px;
    transform: translateX(430px) scale(0.78);
    z-index: 6;
}

/* 3rd small card */
.slide.next3 {
    opacity: 1;
    width: 180px;
    transform: translateX(580px) scale(0.7);
    z-index: 4;
}

/* Animation */
.slide.slide-in { animation: slideIn 0.7s ease forwards; }
.slide.slide-out { animation: slideOut 0.7s ease forwards; }

@keyframes slideIn {
    from { transform: translateX(300px) scale(0.7); opacity: 0.5; }
    to   { transform: translateX(0) scale(1); opacity: 1; }
}

@keyframes slideOut {
    from { transform: translateX(0) scale(1); opacity: 1; }
    to   { transform: translateX(-150px) scale(0.6); opacity: 0; }
}

/* Navigation Buttons */
.slider-controls {
    margin-top: 20px;
    display: flex;
    gap: 25px;
}

.slider-controls button {
    padding: 10px 18px;
    background: #5C3A21;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 20px;
    cursor: pointer;
}

.slider-controls button:hover { background: #9d6e4c; }

/* Animal Details */
.slider-details {
    margin-top: 15px;
    text-align: center;
    color: #5C3A21;
    font-family: Arial, sans-serif;
}

.slider-details h2 { margin-bottom: 5px; font-size: 26px; }
.slider-details p { margin: 2px 0; font-size: 18px; }
</style>
</head>

<body>

<header>
    <div class="logo">
        <img src="/paws&protect/includes/image/paw.png" alt="Logo">
    </div>
    <nav class="nav-links">
        <li><a href="/paws&protect/home.php">HOME</a></li>
        <li><a href="/paws&protect/includes/page/about.php">ABOUT</a></li>
        <li><a href="adopt.php">ADOPT</a></li>
        <li><a href="rescue.php">RESCUE</a></li>
        <li><a href="donate.php">DONATE</a></li>
        <li><a href="lost.php">LOST</a></li>
    </nav>
    <div class="menu-toggle" id="menu-toggle"><i class="fa fa-bars"></i></div>
</header>

<div class="mobile-nav" id="mobile-nav">
    <a href="/paws&protect/home.php">HOME</a>
    <a href="/paws&protect/includes/page/about.php">ABOUT</a>
    <a href="adopt.php">ADOPT</a>
    <a href="rescue.php">RESCUE</a>
    <a href="donate.php">DONATE</a>
    <a href="lost.php">LOST</a>
</div>

<div class="hero">
    <img src="/paws&protect/includes/image/ad1.png" alt="about">
    <div class="hero-text">Adopt Animals</div>
</div>

<section class="smooth-slider">
    <div class="slider-container">
        <div class="slide active" data-name="Fluffy" data-type="Cat" data-age="2 years">
            <img src="/paws&protect/includes/image/cat.png">
        </div>
        <div class="slide next1" data-name="Buddy" data-type="Dog" data-age="3 years">
            <img src="/paws&protect/includes/image/dog.png">
        </div>
        <div class="slide next2" data-name="Thumper" data-type="Rabbit" data-age="1 year">
            <img src="/paws&protect/includes/image/rabbit.png">
        </div>
        <div class="slide next3" data-name="Tweety" data-type="Bird" data-age="6 months">
            <img src="/paws&protect/includes/image/bird.png">
        </div>
    </div>

    <div class="slider-details">
        <h2 id="animal-name">Fluffy</h2>
        <p id="animal-type">Cat</p>
        <p id="animal-age">2 years</p>
    </div>

    <div class="slider-controls">
        <button id="prevBtn">❮</button>
        <button id="nextBtn">❯</button>
    </div>
</section>

<footer>
    <img src="/paws&protect/includes/image/paw.png" alt="paw Logo">
    <div class="right">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
    <p>&copy; 2025 Paws & Protect | Together for Animals 🐾</p>
</footer>

<script>
document.getElementById("menu-toggle").onclick = function() {
    document.getElementById("mobile-nav").classList.toggle("active");
};

let index = 0;
const slides = document.querySelectorAll(".slide");

function updateDetails() {
    const currentSlide = slides[index % slides.length];
    document.getElementById("animal-name").innerText = currentSlide.dataset.name;
    document.getElementById("animal-type").innerText = currentSlide.dataset.type;
    document.getElementById("animal-age").innerText = currentSlide.dataset.age;
}

function updateSlides(direction = "next") {
    slides.forEach(slide => {
        slide.classList.remove("active","next1","next2","next3","slide-in","slide-out");
    });

    const total = slides.length;
    const current = slides[index % total];
    const next1 = slides[(index + 1) % total];
    const next2 = slides[(index + 2) % total];
    const next3 = slides[(index + 3) % total];

    if(direction === "next") {
        next1.classList.add("slide-in");
        current.classList.add("slide-out");
    } else if(direction === "prev") {
        current.classList.add("slide-in");
        next3.classList.add("slide-out");
    }

    current.classList.add("active");
    next1.classList.add("next1");
    next2.classList.add("next2");
    next3.classList.add("next3");

    updateDetails();
}

document.getElementById("nextBtn").onclick = () => {
    index++;
    updateSlides("next");
};

document.getElementById("prevBtn").onclick = () => {
    index--;
    if(index < 0) index = slides.length - 1;
    updateSlides("prev");
};

// Initial setup
updateSlides();
</script>

</body>
</html>
