<?php 

$server="localhost";
$user="root";
$password="";
$dbase="paws";

$conn=mysqli_connect($server, $user, $password, $dbase );

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 ?>
