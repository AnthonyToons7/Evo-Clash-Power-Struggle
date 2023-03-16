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
    $qry = "SELECT PLAYER_1_CHARACTER_ID, PLAYER_1_CHARACTER_ID_2, PLAYER_2_CHARACTER_ID, PLAYER_2_CHARACTER_ID_2 
    FROM active_games WHERE ACTIVE_GAMES_ID = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $_GET["id"]);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $row = $result->fetch_assoc();

    $data = array(
        "player1" =>[
            array("character_id_1",$row['PLAYER_1_CHARACTER_ID']),
            array("character_id_2",$row['PLAYER_1_CHARACTER_ID_2']),
        ],
        "player2" =>[
            array("character_id_1",$row['PLAYER_2_CHARACTER_ID']),
            array("character_id_2",$row['PLAYER_2_CHARACTER_ID_2']),
        ]
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);

    // $character_data =[
    //     'player1' => [
    //         "character_id_1" => $row['PLAYER_1_CHARACTER_ID'],
    //         "character_id_2" => $row['PLAYER_1_CHARACTER_ID_2']
    //     ],
    //     'player2' => [
    //         "character_id_1" => $row['PLAYER_1_CHARACTER_ID'],
    //         "character_id_2" => $row['PLAYER_1_CHARACTER_ID_2']
    //     ]
    // ];
    // header('Content-Type: application/json; charset=utf-8');
    // echo json_encode($data);

    // $data = [
    //     'player1' => [
    //         "character_id_1" => $row['PLAYER_1_CHARACTER_ID'],
    //         "character_id_2" => $row['PLAYER_1_CHARACTER_ID_2']
    //     ],
    //     'player2' => [
    //         "character_id_1" => $row['PLAYER_1_CHARACTER_ID'],
    //         "character_id_2" => $row['PLAYER_1_CHARACTER_ID_2']
    //     ]
    // ];
