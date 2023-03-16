<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
$err = "";
$content = "";
if(isset($_GET["error"])){
    $err = $_GET["error"];
}
    $mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
    // $qry = "SELECT ATTACK_MOVES_NAME FROM attack_moves WHERE ATTACK_MOVE_ID = ?;";
    $qry = "SELECT am.ATTACK_MOVES_NAME, am.ATTACK_MOVE_RANGE_X, am.ATTACK_MOVE_RANGE_Y
    FROM attack_moves AS am
    JOIN character_attack_moves AS cam ON cam.ATTACK_MOVE_ID = am.ATTACK_MOVE_ID
    WHERE cam.ATTACK_MOVE_ID = ? AND cam.CHARACTER_ID = ?;
    ";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('ii', $_GET['move'], $_GET['char']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row !== null) {
        $data = [
            'attack' => [
                "name" => $row['ATTACK_MOVES_NAME'],
                "xRange" => $row['ATTACK_MOVE_RANGE_X'],
                "yRange" => $row['ATTACK_MOVE_RANGE_Y'],
            ],
        ];
    } else {
        $data = [
            'error' => 'No rows found',
        ];
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);

    