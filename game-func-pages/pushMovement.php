<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
$err = "";
$content = "";
if(isset($_GET["error"])){
    $err = $_GET["error"];
}
$roomID = $_GET['id'];
$character = $_GET["character"];
$x = $_GET['x'];
$y = $_GET['y'];
$player = $_GET['player'];
$count = $_GET['charcount'];
$mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
if ($count == 1){
    $qry = "UPDATE character_positions SET X_VALUE = ?, Y_VALUE = ? WHERE ACTIVE_GAME_ID = ? AND ACTIVE_USER_ID = ? AND CHARACTER_ID_1 = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iiiii', $x, $y, $roomID, $player, $character);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();


    // $qry = "UPDATE character_positions SET `X_VALUE` = $x, `Y_VALUE` = $y WHERE `ACTIVE_GAME_ID` = $roomID AND `ACTIVE_USER_ID` = $player AND `CHARACTER_ID_1` = $character;";
    // $mysqli_stmt = $mysqli->prepare($qry);
    // $mysqli_stmt->execute();
    // $result = $mysqli_stmt->get_result();

} else if ($count == 2){
    $qry = "UPDATE character_positions SET X_VALUE = ?, Y_VALUE = ? WHERE ACTIVE_GAME_ID = ? AND ACTIVE_USER_ID = ? AND CHARACTER_ID_2 = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iiiii', $x, $y, $roomID, $player, $character);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
}
$arr[]=[
    "x" => $x,
    "y" => $y
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($arr);