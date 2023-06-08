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
$id = $_SESSION["USERS_ID"];
$mysqli = new mysqli($one, $two, $three, $four);
$qry = "INSERT INTO active_games (PLAYER_1_USER_ID) VALUES (?);";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt->bind_param('i', $id);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();


$qry = "SELECT ACTIVE_GAMES_ID FROM active_games WHERE PLAYER_1_USER_ID= ?";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt->bind_param('i', $id);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room</title>
    <script>
        function copyLink() {
        var copyText = document.getElementById("link");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        alert("Copied link: " + copyText.value);
        window.location.replace(copyText.value);
        }
    </script>
    <style>
        #link{
            pointer-events:none;
            border:none;
            width: -webkit-fill-available;
            background:unset;
            color:#fff;
        }
        body {
            background-color: #343434;
        }
    </style>
</head>
<body>
    <input type="text" name="" id="link" value="<?= "https://anthonytoons.nl/rpg_evo_clash/pages/character-selection.php?id=" . $row['ACTIVE_GAMES_ID']?>">
    <button onclick="copyLink()">Copy link</button>
</body>
</html>