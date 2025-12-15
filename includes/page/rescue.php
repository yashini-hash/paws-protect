<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Protect </title>
     <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
    <link rel="stylesheet" href="com.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

  

</head>
<body>

   
    <header>
        <div class="logo">
            <img src="/paws&protect/includes/image/paw.png" alt=" Logo">
        </div>

      
        <nav class="nav-links">
           <li><a href="/paws&protect/home.php" > HOME</a></li>
           <li><a href="/paws&protect/includes/page/about.php">ABOUT</a></li>
           <li><a href="adopt.php">ADOPT</a></li>
           <li> <a href="rescue.php">RESCUE</a></li>
           <li> <a href="donate.php">DONATE</a></li>
            <li><a href="lost.php">LOST</a></li>
            
        </nav>

        
        <div class="menu-toggle" id="menu-toggle">
            <i class="fa fa-bars"></i>
        </div>
    </header>

   
    <div class="mobile-nav" id="mobile-nav">
        <a href="/paws&protect/home.php">HOME</a>
            <a href="/paws&protect/includes/page/about.php">ABOUT</a>
            <a href="adopt.php">ADOPT</a>
            <a href="rescue.php">RESCUE</a>
            <a href="donate.php">DONATE</a>
            <a href="lost.php">LOST</a>
    </div>





    <script>
 
    document.getElementById("menu-toggle").onclick = function() {
      document.getElementById("mobile-nav").classList.toggle("active");
    };
    </script>


       <div class="hero">
       <img src="/paws&protect/includes/image/rescuehero.png" alt="about">
  <div class="hero-text">Rescue Animals</div>
</div>



      

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

</body>
</html>
