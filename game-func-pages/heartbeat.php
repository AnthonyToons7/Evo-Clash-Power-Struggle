<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
$err = "";
$content = "";


// Connect to the database
$mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");

$id = $_GET['id'];
$request = $_GET['request'];

$qry = "SELECT CURRENT_TURN_PLAYER, PLAYER_1_USER_ID, PLAYER_2_USER_ID FROM active_games WHERE ACTIVE_GAMES_ID = ?";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt->bind_param('i', $id);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();
$yes = $result->fetch_assoc();

$qry = "SELECT CURRENT_TURN_PLAYER, CURRENT_TURN FROM active_games WHERE ACTIVE_GAMES_ID = ?";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt->bind_param('i', $id);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();
$res = $result->fetch_assoc();
if ($res['CURRENT_TURN'] > 10) $res['CURRENT_TURN'] = $res['CURRENT_TURN'] / 4;

$arr[]=[
    "player"=>$res['CURRENT_TURN_PLAYER'],
    "turncount_"=>$res['CURRENT_TURN']
];
$turn = $res['CURRENT_TURN'];
if ($request === 'PUSHTURN'){
    $turn++;
    $qry = "UPDATE active_games SET CURRENT_TURN_PLAYER = ?, CURRENT_TURN = ? WHERE ACTIVE_GAMES_ID = ?;"; 
    $mysqli_stmt = $mysqli->prepare($qry);

    if ($yes['CURRENT_TURN_PLAYER'] != $yes['PLAYER_1_USER_ID']){
        $mysqli_stmt->bind_param('iii', $yes['PLAYER_1_USER_ID'], $turn, $id);
    } else {
        $mysqli_stmt->bind_param('iii', $yes['PLAYER_2_USER_ID'], $turn, $id);
    }
    
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $arr[]=[
        "player"=>$res['CURRENT_TURN_PLAYER'],
        "turncount_"=>$res['CURRENT_TURN']
    ];
}
else if ($request === "WINNER"){
    $play1char1 = $_GET["play1char1"];
    $play1char2 = $_GET["play1char2"];
    $play2char1 = $_GET["play2char1"];
    $play2char2 = $_GET["play2char2"];

    $qry = "SELECT CHARACTER_HP FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_1 = ?";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $id, $play1char1);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $play1char1Data = $result->fetch_assoc();

    $qry = "SELECT CHARACTER_HP FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_2 = ?";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $id, $play1char2);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $play1char2Data = $result->fetch_assoc();

    $qry = "SELECT CHARACTER_HP FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_1 = ?";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $id, $play2char1);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $play2char1Data = $result->fetch_assoc();

    $qry = "SELECT CHARACTER_HP FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_2 = ?";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $id, $play2char2);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $play2char2Data = $result->fetch_assoc();

    $arr[]=[
        "hp11"=>$play1char1Data,
        "hp12"=>$play1char2Data,
        "hp21"=>$play2char1Data,
        "hp22"=>$play2char2Data,
    ];
}
else if ($request == "CORRECTPOSITIONS"){
    $play1char1 = $_GET["play1char1"];
    $play1char2 = $_GET["play1char2"];
    $play2char1 = $_GET["play2char1"];
    $play2char2 = $_GET["play2char2"];

    $qry = "SELECT X_VALUE, Y_VALUE FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_1 = ?";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $id, $play1char1);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $play1char1Data = $result->fetch_assoc();

    $qry = "SELECT X_VALUE, Y_VALUE FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_2 = ?";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $id, $play1char2);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $play1char2Data = $result->fetch_assoc();

    $qry = "SELECT X_VALUE, Y_VALUE FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_1 = ?";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $id, $play2char1);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $play2char1Data = $result->fetch_assoc();

    $qry = "SELECT X_VALUE, Y_VALUE FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_2 = ?";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $id, $play2char2);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $play2char2Data = $result->fetch_assoc();

    $arr[]=[
        "play1char1"=>$play1char1Data,
        "play1char2"=>$play1char2Data,
        "play2char1"=>$play2char1Data,
        "play2char2"=>$play2char2Data,
    ];
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($arr);