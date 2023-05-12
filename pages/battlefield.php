<?php 
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!isset($_SESSION["USERS_ID"]) || !$_SESSION["USERS_ID"]){
    header("Location:../../login.php");
}
$err = "";
$content = "";
if(isset($_GET["error"])){
    $err = $_GET["error"];
}

    // $mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
    // $qry = "SELECT * FROM active_games WHERE PLAYER_1_USER_ID = ?;";
    // $mysqli_stmt = $mysqli->prepare($qry);
    // $mysqli_stmt->bind_param('i', $_SESSION["USERS_ID"]);
    // $mysqli_stmt->execute();
    // $result = $mysqli_stmt->get_result();
    // if($result->num_rows === 0){
    //     header("Location: http://localhost/pages/startmenu.php");
    // }

    $mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
    $qry = "SELECT * FROM active_games WHERE ACTIVE_GAMES_ID = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $_GET["id"]);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $row = $result->fetch_assoc();
    if($result->num_rows === 0){
        die();
        header("Location: http://localhost/pages/startmenu.php");
    }    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battlefield</title>
    <link rel="stylesheet" href="../css/battlefield.css">
    <script src="../js/ajax-jquery/ajax.js"></script>
    <script src="../js/ajax-jquery/jquery-3.6.1.js"></script>
<body>
    <main>
        <section id="grid-container">
            <div id="grid"></div>
        </section>
        <section id="moves-container">
            <div id="atk-moves-description">
                <div id="description-title"><h1>...</h1></div>
                <div id="description-desc"><p>...</p></div>
            </div>
            <div id="attack-moves-container">
                <div id="moves-1" class="attack-moves">
                    <img src="" alt="">
                    <div>1</div>
                </div>
                <div id="moves-2" class="attack-moves">
                    <img src="" alt="">
                    <div>2</div>
                </div>
                <div id="moves-3" class="attack-moves">
                    <img src="" alt="">
                    <div>3</div>
                </div>
                <div id="moves-evolve" class="attack-moves">
                    <img src="" alt="">
                    <div>4</div>
                </div>
                <div id="moves-4" class="attack-moves" style="font-size:20px; font-weight:600;" onclick="turnReset();">
                    <div>Start</div>
                </div>
            </div>
        </section>
        <section id="turn-counter">
            <div class="character-turn-box" id="character-turn-1">
                <div id="character-hp-small-1" class="small-hp-bar"></div>
                <div src="" alt="" class="renderBoxes" id='renderbox-turn-1'></div>
            </div>
            <div class="character-turn-box" id="character-turn-2">
                <div id="character-hp-small-2" class="small-hp-bar"></div>
                <div src="" alt="" class="renderBoxes" id='renderbox-turn-2'></div>
            </div>
            <div class="character-turn-box" id="character-turn-3">
                <div id="character-hp-small-3" class="small-hp-bar"></div>
                <div src="" alt="" class="renderBoxes" id='renderbox-turn-3'></div>
            </div>
            <div class="character-turn-box" id="character-turn-4">
                <div id="character-hp-small-4" class="small-hp-bar"></div>
                <div src="" alt="" class="renderBoxes" id='renderbox-turn-4'></div>
            </div>
        </section>
        <div id="turn-change">
            <h1>Turn change</h1>
            <p id="player-turn-txt">Player 1's turn</p>
        </div>
        <div id="void-evo-container">
            <div id="fated"></div>
            <div id="bloodb"></div>
        </div>
        <div id="small-hp-container">
            <div id="hp-container">
                <div id='hp-bar'>
                    <div id='hp-bar-filler'></div>
                    <div id='hp-bar-filler-damage'></div>
                    <div id='hp-bar-filler-heal'></div>
                </div>
                <div id='hp-number'><p>100</p></div>
            </div>
            <div id='status-effect-container'></div>
            <div></div>
        </div>
    </main>
    <div id="loading-overlay">
        <h1>Loading...</h1>
    </div>
    <div id='attacking-overlay'><div id='attacking-image'></div></div>
    <script src="../js/game.js"></script>
    <script type="text/javascript" defer>
        document.addEventListener("DOMContentLoaded", function(){
            createBattlefield();
            addCanvas(<?=$_GET["id"]?>);
            setRoomID(<?=$_GET["id"]?>);
            getCharacters(<?= $_GET["id"] ?>);
            getUserIDs(<?=$_GET["id"]?>);
            setThisPlayer(<?=$_SESSION["USERS_ID"]?>);
            setNewPlayerID(<?=$_SESSION["USERS_ID"]?>);
            setTimeout(() => {
                turnReset('load-in');
            }, 2000);
        });
    </script>
</body>
</html>