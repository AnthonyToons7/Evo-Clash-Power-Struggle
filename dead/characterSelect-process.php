<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$err = "";
$content = "";
if(isset($_GET["error"])){
    $err = $_GET["error"];
}
var_dump($_POST);
$mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
$qry = "";
$result = $mysqli_stmt = $mysqli->query($qry);
$mysqli->close();