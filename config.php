<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flixnet";

/* Attempt to connect to MySQL database */
$link = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if(!session_id()) 
    session_start();
if(!isset($_SESSION['email'])) {
    $_SESSION['email'] = "";
}

?>
