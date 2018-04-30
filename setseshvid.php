<?php 
require_once "config.php";

if ($_POST["action"])
{
    $_SESSION["vid"] = $_POST["action"];
}
?>