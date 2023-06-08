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
$qry = "SELECT USER_NICKNAME FROM users WHERE USERS_ID = ?";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt -> bind_param("i", $_SESSION['USERS_ID']);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();
while ($row = $result -> fetch_assoc()) { 
    $content .= "<div class='welcome_box'>";
    $content .= "<p>Welcome " . $row['USER_NICKNAME'] . "</p>";
    $content .= "</div>";
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body onload="setPage('startmenu')">
    <div id="bg-img"></div>
    <main>
        <div class="title-border-overlay">
            <div id="title-box" class="title-menu-box">
                <p class="title-menu-text-box">Evo Clash: Power Struggle</p>
            </div>
        </div>
        <div class="start-border-overlay">
            <div id="start-box" class="start-menu-box">
                <p class="start-menu-text-box">Start</p>
            </div>
        </div>
        <div class="menu-boxes-container">
            <div class="double-border-overlay">
                <div class="border-overlay">
                    <a href="arena.php">
                        <div id="arena-box" class="menu-boxes">
                            <p class="menu-text-box">Arena</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="double-border-overlay">
                <div class="border-overlay">
                    <a href="characters.php">
                        <div id="character-box" class="menu-boxes">
                            <p class="menu-text-box">Characters</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="double-border-overlay">
                <div class="border-overlay">
                    <a href="updates.php">
                        <div id="update-box" class="menu-boxes">
                            <p class="menu-text-box">Updates</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="double-border-overlay">
                <div class="border-overlay">
                    <a href="../func-pages/account.php">
                        <div id="account-box" class="menu-boxes">
                            <p class="menu-text-box">Account</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="double-border-overlay">
                <div class="border-overlay">
                    <a href="IkWilGeldGEEFMIJGELD.php">
                        <div id="support-box" class="menu-boxes">
                            <p class="menu-text-box">Support &hearts;</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <?= $content ?>
    </main>
    <script src="../js/menu.js"></script>
</body>
</html>