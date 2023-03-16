<?php    
    $mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");

$id=$_GET['id'];


    $qry = "SELECT * FROM character_positions WHERE ACTIVE_GAME_ID = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $id);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    
    $output = array();
    while ($row = $result->fetch_assoc()) {
        $output[] = $row;
    }


    $qry = "SELECT X_VALUE, Y_VALUE FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_1 = ? AND ACTIVE_USER_ID=?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $id, $output[0]['CHARACTER_ID_1'], $output[0]['ACTIVE_USER_ID']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $player1Char1 = $result->fetch_assoc();

    $qry = "SELECT X_VALUE, Y_VALUE FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_2 = ? AND ACTIVE_USER_ID=?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $id,  $output[1]['CHARACTER_ID_2'], $output[1]['ACTIVE_USER_ID']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $player1Char2 = $result->fetch_assoc();

    $qry = "SELECT X_VALUE, Y_VALUE FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_1 = ? AND ACTIVE_USER_ID=?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $id,  $output[2]['CHARACTER_ID_1'], $output[2]['ACTIVE_USER_ID']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $player2Char1 = $result->fetch_assoc();

    $qry = "SELECT X_VALUE, Y_VALUE FROM character_positions WHERE ACTIVE_GAME_ID = ? AND CHARACTER_ID_2 = ? AND ACTIVE_USER_ID=?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $id,  $output[3]['CHARACTER_ID_2'], $output[3]['ACTIVE_USER_ID']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $player2Char2 = $result->fetch_assoc();


    if(
        empty($player1Char1) ||
        empty($player1Char2) ||
        empty($player2Char1) ||
        empty($player2Char2)
    ) {
        $info = [
            'error' => 'No rows found',
        ];
    } else {
        $info = [
            'p1c1X' => $player1Char1['X_VALUE'],
            'p1c1Y' => $player1Char1['Y_VALUE'],

            'p1c2X' => $player1Char2['X_VALUE'],
            'p1c2Y' => $player1Char2['Y_VALUE'],

            'p2c1X' => $player2Char1['X_VALUE'],
            'p2c1Y' => $player2Char1['Y_VALUE'],

            'p2c2X' => $player2Char2['X_VALUE'],
            'p2c2Y' => $player2Char2['Y_VALUE'],
        ];
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($info);

