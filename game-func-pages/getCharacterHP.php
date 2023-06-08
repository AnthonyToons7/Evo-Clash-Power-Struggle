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
$charid = $_GET['characterid'];
$ui = $_GET['userid'];
$nmbr = $_GET['nmbr']; 
$mysqli = new mysqli($one, $two, $three, $four);
if ($nmbr == 1){
    $qry = "SELECT CHARACTER_HP FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_1 = ? AND ACTIVE_USER_ID=?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $id, $charid, $ui);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $row = $result->fetch_assoc();
} else if ($nmbr == 2){
    $qry = "SELECT CHARACTER_HP FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_2 = ? AND ACTIVE_USER_ID=?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $id, $charid, $ui);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $row = $result->fetch_assoc();
}
    if(empty($row['CHARACTER_HP'])) {
        $data = [
            'error' => 'No rows found',
        ];
    } else {
        $data = [
            'hp' => $row['CHARACTER_HP'],
        ];
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);

    