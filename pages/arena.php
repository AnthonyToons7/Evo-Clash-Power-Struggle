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
    $qry = "SELECT USER_NICKNAME, USER_VICTORIES FROM users ORDER BY USER_VICTORIES DESC";
    $result = $mysqli_stmt = $mysqli->query($qry);
    $i = 1;
    while ($row = $result -> fetch_assoc()) { 
        if($i % 2 == 0) {
            if ($i == 2){
                $content .= "<tr class=\"dark-gray second-place\">";
                $content .= "<td>#" . $i . "</td>";
                $content .= "<td>" . $row["USER_NICKNAME"] . "</td>";
                $content .= "<td>" . $row["USER_VICTORIES"] . "</td>";
                $content .= "</tr>";
            } else{
                $content .= "<tr class=\"dark-gray\">";
                $content .= "<td>#" . $i . "</td>";
                $content .= "<td>" . $row["USER_NICKNAME"] . "</td>";
                $content .= "<td>" . $row["USER_VICTORIES"] . "</td>";
                $content .= "</tr>";
            }
        } else {
            if ($i == 1){
                $content .= "<tr class=\"light-gray first-place\">";
                $content .= "<td>#" . $i . "</td>";
                $content .= "<td>" . $row["USER_NICKNAME"] . "</td>";
                $content .= "<td>" . $row["USER_VICTORIES"] . "</td>";
                $content .= "</tr>";
            } else if ($i == 3){
                $content .= "<tr class=\"light-gray third-place\">";
                $content .= "<td>#" . $i . "</td>";
                $content .= "<td>" . $row["USER_NICKNAME"] . "</td>";
                $content .= "<td>" . $row["USER_VICTORIES"] . "</td>";
                $content .= "</tr>";
            } else {
                $content .= "<tr class=\"dark-gray\">";
                $content .= "<td>#" . $i . "</td>";
                $content .= "<td>" . $row["USER_NICKNAME"] . "</td>";
                $content .= "<td>" . $row["USER_VICTORIES"] . "</td>";
                $content .= "</tr>";
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
    <title>Arena</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body onload="setPage('arena')">
    <main>
        <section id="arena-container">
            <div id="leaderboard-container">
                <div id="leaderboard-top">
                    <h1>Leaderboard</h1>
                </div>
                <table>
                    <tr id="table-first">
                        <td>Place</td>
                        <td>Player</td>
                        <td>Wins</td>
                    </tr>                    
                    <?php
                        echo $content;
                        echo $err;
                    ?>
                </table>
            </div>
            <div class="menu-boxes-container">
                <div class="double-room-border-overlay">
                    <div class="room-border-overlay border-overlay">
                        <a href="../game-func-pages/roomHandler.php">
                            <div id="create-room-box" class="room-menu-boxes">
                                <p class="menu-text-box">Create room</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="double-room-border-overlay">
                    <div class="room-border-overlay border-overlay">
                        <a href="game">
                            <div id="join-room-box" class="room-menu-boxes">
                                <p class="menu-text-box">Join room</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="double-room-border-overlay">
                    <div class="room-border-overlay border-overlay">
                        <a href="startmenu.php">
                            <div id="back-box" class="room-menu-boxes">
                                <p class="menu-text-box">Back</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="../js/menu.js"></script>
</body>
</html>