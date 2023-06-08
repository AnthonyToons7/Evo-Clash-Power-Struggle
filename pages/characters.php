<?php
    include '../ww.php';
    session_start();
    if (!isset($_SESSION["USERS_ID"]) || !$_SESSION["USERS_ID"]){
        header("Location:../../login.php");
    }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $err = "";
    $content = "";
	if(isset($_GET["error"])){
		$err = $_GET["error"];
	}

    $mysqli = new mysqli($one, $two, $three, $four);
    $qry = "SELECT CHARACTER_NAME FROM characters";
    $result = $mysqli_stmt = $mysqli->query($qry);
    $i = 1;
    while ($row = $result -> fetch_assoc()) { 
        if ($i < 7){
            if ($i == 1){
                $content .= "<div class='character-selection-box selected'>";
                $content .= "<div class='characters-" . $row['CHARACTER_NAME'] ." characters'></div>";
                $content .= "<span>" .  $row['CHARACTER_NAME'] . "</span>";
                $content .= "</div>";
            } else {
                $content .= "<div class='character-selection-box'>";
                $content .= "<div class='characters-" . $row['CHARACTER_NAME'] ." characters'></div>";
                $content .= "<span>" .  $row['CHARACTER_NAME'] . "</span>";
                $content .= "</div>";
            }
        }
        $i++;
    }
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Characters</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/characters.css">
    <script src="../js/characters.js"></script>
    <script src="../js/ajax-jquery/jquery-3.6.1.js"></script>
</head>
<body>
    <head></head>
    <main>          
        <section id="characters-container">
            <div id="character-flexbox-left">
                <div class='characters-container'>
                    <?= $content ?>
                </div>
                <svg viewBox="0 0 15 7">
                    <path d="M 0 0 H 0 V 0 H 7 V 7 H 15" stroke="#8a2be2" stroke-width="0.5" fill="none" class='path' />
                </svg>
            </div>
            <div id="character-flexbox-right">
                <a href="top"></a>
                <div id="character-desc-container">
                    <div id="character-desc-content">
                        <div><h1 class='changeTxt' id='character-title'></h1></div>
                        <div class='generic-info'>
                            <p>Full name: </p>
                            <p class='changeTxt'></p>
                        </div>
                        <div class='generic-info'>
                            <p>Age: </p>
                            <p class='changeTxt'></p>
                        </div>
                        <div class='generic-info'>
                            <p>Hair color: </p>
                            <p class='changeTxt'></p>
                        </div>
                        <div class='generic-info'>
                            <p>Species: </p>
                            <p class='changeTxt'></p>
                        </div>
                        <div class='generic-info'>
                            <p>Quote:</p>
                            <p class='changeTxt' id='italic'></p>
                        </div>
                        <div id='flexbox'>
                            <div class='atk-box-1'>
                                <img src="" alt="" id='box-1-img' class='atk-box-img'>
                                <div class='attack-desc-container'>
                                    <h1 class='changeTxt'></h1>
                                    <p class='changeTxt'></p>
                                </div>
                            </div>
                            <div class='atk-box-2'>
                                <img src="" alt="" id='box-2-img' class='atk-box-img'>
                                <div class='attack-desc-container'>
                                    <h1 class='changeTxt'></h1>
                                    <p class='changeTxt'></p>
                                </div>
                            </div>
                            <div class='atk-box-3'>
                                <img src="" alt="" id='box-3-img' class='atk-box-img'>
                                <div class='attack-desc-container'>
                                    <h1 class='changeTxt'></h1>
                                    <p class='changeTxt'></p>
                                </div>
                            </div>
                        </div>
                        <div id='info-bottom'>
                            <div id='character-render'>
                                <!-- <img src="../img/character-renders/void-render.png" alt="" id='render'> -->
                            </div>
                            <div id='btn-container'>
                                <div id='btn-left'><div id="arrow-left"></div></div>
                                <div id='btn-right'><div id="arrow-right"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer></footer>
</body>
</html>