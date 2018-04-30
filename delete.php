<?php 
require_once "config.php";

$email = $_SESSION["email"];

if(!isset($email) || empty($email)){
    header("location: signin.php");
    exit;
}

$result = mysqli_query($link, "SELECT * FROM prof WHERE email = '$email'");
$row = mysqli_fetch_assoc($result);
$pid = $row["pid"];

mysqli_query($link, "DELETE FROM owner WHERE pid = '$pid'");
mysqli_query($link, "DELETE FROM subscription WHERE pid = '$pid'");
mysqli_query($link, "DELETE FROM prof WHERE pid = '$pid'");
$_SESSION["email"] = "";
header("location: signin.php");
?>