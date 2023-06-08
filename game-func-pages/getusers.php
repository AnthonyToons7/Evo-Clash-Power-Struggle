<?php
include '../ww.php';
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
$err = "";
$content = "";
if(isset($_GET["error"])){
    $err = $_GET["error"];
}
$id = $_GET['id'];
$mysqli = new mysqli($one, $two, $three, $four);
$qry = "SELECT PLAYER_1_USER_ID, PLAYER_2_USER_ID FROM active_games WHERE ACTIVE_GAMES_ID = ?;";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt->bind_param('i', $id);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();
$row = $result->fetch_assoc();


if ($row !== null) {
    $data = [
        "gameid" => $id,
        "player1" => $row['PLAYER_1_USER_ID'],
        "player2" => $row['PLAYER_2_USER_ID']
    ];
} else {
    $data = [
        'error' => 'No rows found'
    ];
}    
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);