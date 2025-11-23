<?php 

$server="localhost";
$user="root";
$password="";
$dbase="paws";

$conn=mysqli_connect($server, $user, $password, $dbase );

if(!$conn){

	die("Connection failed: ".mysqli_error());

}

 ?>
