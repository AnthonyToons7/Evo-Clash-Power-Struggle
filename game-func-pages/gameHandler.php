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
$playerID = $_SESSION["USERS_ID"];
$character1 = $_POST['characterCheckbox'][0];
$character2 = $_POST['characterCheckbox'][1];

$mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
$qry = "SELECT * FROM active_games WHERE ACTIVE_GAMES_ID=? OR PLAYER_1_USER_ID= ?;";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt->bind_param('ii', $roomID, $playerID);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();
$row = $result->fetch_assoc();
if ($playerID===$row['PLAYER_1_USER_ID']) {
    if (!empty($_POST)) {
        $qry = "UPDATE active_games SET `PLAYER_1_CHARACTER_ID` = $character1, `PLAYER_1_CHARACTER_ID_2` = $character2, `CURRENT_TURN_PLAYER` = $playerID, `CURRENT_TURN` = 0 WHERE `active_games` . `ACTIVE_GAMES_ID` = $roomID";
        $mysqli_stmt = $mysqli->prepare($qry);
        $mysqli_stmt->execute();
    
        $result = $mysqli_stmt->get_result();
    }
    
    $posX = 0;
    $posY = 0;
    $NULLcharacter = NULL;
    $qry = "SELECT CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF, CHARACTER_COOLDOWN FROM characters WHERE CHARACTERS_ID = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $character1);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $statData = $result->fetch_assoc();

    $qry = "INSERT INTO character_positions (ACTIVE_GAME_ID, ACTIVE_USER_ID, CHARACTER_ID_1, 
    CHARACTER_ID_2, X_VALUE, Y_VALUE, CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iiiiiiiii', $roomID, $playerID, $character1, $NULLcharacter, $posX, $posY, $statData['CHARACTER_HP'], $statData['CHARACTER_ATK'],
    $statData['CHARACTER_DEF']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    
    $posX = 1;
    $posY = 4;
    $qry = "SELECT CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF, CHARACTER_COOLDOWN FROM characters WHERE CHARACTERS_ID = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $character2);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $statData = $result->fetch_assoc();

    $qry = "INSERT INTO character_positions (ACTIVE_GAME_ID, ACTIVE_USER_ID, CHARACTER_ID_1, 
    CHARACTER_ID_2, X_VALUE, Y_VALUE, CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iiiiiiiii', $roomID, $playerID, $NULLcharacter, $character2, $posX, $posY, $statData['CHARACTER_HP'], $statData['CHARACTER_ATK'],
    $statData['CHARACTER_DEF']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    

} else if ($playerID != $row['PLAYER_1_USER_ID']){
    if (!empty($_POST)) {
        $qry = "UPDATE active_games SET `PLAYER_2_USER_ID` = $playerID, `PLAYER_2_CHARACTER_ID` = $character1, `PLAYER_2_CHARACTER_ID_2` = $character2 WHERE `active_games` . `ACTIVE_GAMES_ID` = $roomID";
        $mysqli_stmt = $mysqli->prepare($qry);
        $mysqli_stmt->execute();
    
        $result = $mysqli_stmt->get_result();
    }
        
    $posX = 7;
    $posY = 1;
    $NULLcharacter = NULL;
    $qry = "SELECT CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF, CHARACTER_COOLDOWN FROM characters WHERE CHARACTERS_ID = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $character1);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $statData = $result->fetch_assoc();

    $qry = "INSERT INTO character_positions (ACTIVE_GAME_ID, ACTIVE_USER_ID, CHARACTER_ID_1, 
    CHARACTER_ID_2, X_VALUE, Y_VALUE, CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iiiiiiiii', $roomID, $playerID, $character1, $NULLcharacter, $posX, $posY, $statData['CHARACTER_HP'], $statData['CHARACTER_ATK'],
    $statData['CHARACTER_DEF']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    
    $posX = 7;
    $posY = 4;
    $qry = "SELECT CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF, CHARACTER_COOLDOWN FROM characters WHERE CHARACTERS_ID = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $character2);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $statData = $result->fetch_assoc();

    $qry = "INSERT INTO character_positions (ACTIVE_GAME_ID, ACTIVE_USER_ID, CHARACTER_ID_1, 
    CHARACTER_ID_2, X_VALUE, Y_VALUE, CHARACTER_HP, CHARACTER_ATK, CHARACTER_DEF) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iiiiiiiii', $roomID, $playerID, $NULLcharacter, $character2, $posX, $posY, $statData['CHARACTER_HP'], $statData['CHARACTER_ATK'],
    $statData['CHARACTER_DEF']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
}
    $qry = "SELECT ACTIVE_GAMES_ID FROM active_games WHERE PLAYER_1_USER_ID= ? AND PLAYER_1_CHARACTER_ID= ? AND PLAYER_1_CHARACTER_ID_2= ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('iii', $playerID, $character1, $character2);
    $mysqli_stmt->execute();

    $result = $mysqli_stmt->get_result();
    $gameID = $result->fetch_assoc();    


    // 
    // header("Location: http://localhost/pages/battlefield.php?id=" . $roomID);





function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
$myuuid = '<a href="http://localhost?' . guidv4() . '">http://localhost/' . guidv4() . '</a>';
echo $myuuid;
