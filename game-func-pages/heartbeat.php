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

header('Content-Type: application/json; charset=utf-8');
echo json_encode($arr);