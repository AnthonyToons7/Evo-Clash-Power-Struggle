<?php
include '../ww.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = $_GET["player"];
$mysqli = new mysqli($one, $two, $three, $four);

$qry = "SELECT USER_VICTORIES FROM users WHERE USERS_ID = ?";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt -> bind_param("i", $user);
$mysqli_stmt->execute();
$number = $mysqli_stmt->get_result();
$newNumber = $number -> fetch_assoc();
$newNumber = $newNumber["USER_VICTORIES"] + 1;

$qry = "UPDATE users SET USER_VICTORIES = $newNumber WHERE USERS_ID = $user";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();
$mysqli->close();

$arr = [
    "asd"=>"asd"
];

header("Location: https://anthonytoons.nl/rpg_evo_clash/pages/battlefield.php?id=" . $roomID);
// echo json_encode($arr);