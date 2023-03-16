<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
$err = "";
$content = "";
if(isset($_GET["error"])){
    $err = $_GET["error"];
}

$selectedMove = $_GET['atkname'];
$roomID = $_GET['id'];
$attacker = $_GET["attacker"];
$defender = $_GET['defender'];
$attackingPlayer = $_GET['attackingplayer'];
$defendingplayer = $_GET['defendingplayer'];
$DEFcount = $_GET['DEFcharcount']; 
$ATKcount = $_GET['ATKcount']; 
$type = $_GET['type'];

$mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
if ($ATKcount == 1){
    // get stats attacker
    $qry = "SELECT CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF FROM character_positions WHERE ACTIVE_GAME_ID = ? AND ACTIVE_USER_ID = ? AND CHARACTER_ID_1 = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $roomID, $attackingPlayer, $attacker);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $resultsAttacker = $result->fetch_assoc();

    // get move damage
    $qry = "SELECT ATTACK_MOVES_DAMAGE FROM attack_moves WHERE ATTACK_MOVES_NAME = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $selectedMove);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $damage = $result->fetch_assoc();

} else if ($ATKcount == 2){
    // get stats attacker
    $qry = "SELECT CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF FROM character_positions WHERE ACTIVE_GAME_ID = ? AND ACTIVE_USER_ID = ? AND CHARACTER_ID_2 = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $roomID, $attackingPlayer, $attacker);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $resultsAttacker = $result->fetch_assoc();
   
    // get move damage
    $qry = "SELECT ATTACK_MOVES_DAMAGE FROM attack_moves WHERE ATTACK_MOVES_NAME = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('s', $selectedMove);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $damage = $result->fetch_assoc();

} 
if ($DEFcount == 1){
    // get stats defender
    $qry = "SELECT CHARACTER_HP, CHARACTER_DEF FROM character_positions WHERE ACTIVE_GAME_ID = ? AND ACTIVE_USER_ID = ? AND CHARACTER_ID_1 = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $roomID, $defendingplayer, $defender);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $resultsDefender = $result->fetch_assoc();

}else if ($DEFcount == 2){
    // get stats defender
    $qry = "SELECT CHARACTER_HP, CHARACTER_DEF FROM character_positions WHERE ACTIVE_GAME_ID = ? AND ACTIVE_USER_ID = ? AND CHARACTER_ID_2 = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $roomID, $defendingplayer, $defender);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $resultsDefender = $result->fetch_assoc();
}

$boostsATK = 0;
$boostsDEF = 0;
$totalDamage = $damage['ATTACK_MOVES_DAMAGE'] * (1 + $boostsATK - $boostsDEF);
$effDamage = $totalDamage - ($totalDamage * (($resultsDefender['CHARACTER_DEF'] / 100) - ($resultsAttacker['CHARACTER_ATK'] / 100)));
$hpLeft = $resultsDefender['CHARACTER_HP'] - $effDamage;

$arr[]=[
    "maxhp"=>ceil($resultsDefender['CHARACTER_HP']),
    "damage"=>ceil($effDamage),
    "leftHp"=>ceil($hpLeft),
];

if ($type === 'ATK'){
    $DEFcount == 1 
    ? $qry = "UPDATE character_positions SET CHARACTER_HP = ? WHERE ACTIVE_GAME_ID = ? AND ACTIVE_USER_ID = ? AND CHARACTER_ID_1 = ?;" 
    : $qry = "UPDATE character_positions SET CHARACTER_HP = ? WHERE ACTIVE_GAME_ID = ? AND ACTIVE_USER_ID = ? AND CHARACTER_ID_2 = ?;";

    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iiii', $hpLeft, $roomID, $defendingplayer, $defender);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($arr);