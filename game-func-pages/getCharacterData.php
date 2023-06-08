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
    $characterIDMess = $_GET['characters'];
    $characterIDs = explode(",", $characterIDMess);
    $arr=[];
    $mysqli = new mysqli($one, $two, $three, $four);
    for ($test = 0; $test < count($characterIDs); $test++){
        $qry = "SELECT CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF, CHARACTER_COOLDOWN FROM characters WHERE CHARACTERS_ID = ?;";
        $mysqli_stmt = $mysqli->prepare($qry);
        $mysqli_stmt->bind_param('i', $characterIDs[$test]);
        $mysqli_stmt->execute();
        $result = $mysqli_stmt->get_result();
        $statData = $result->fetch_assoc();
    
        $arr[]=[
            "character_id" => $characterIDs[$test],
            "hp" => $statData['CHARACTER_HP'],
            "atk" => $statData['CHARACTER_ATK'],
            "def" => $statData['CHARACTER_DEF'],
            "cooldown" => $statData['CHARACTER_COOLDOWN']
        ];
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($arr);