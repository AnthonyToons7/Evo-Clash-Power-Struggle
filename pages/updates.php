<?php
  $mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
  $qry = "SELECT UPDATE_TITLE FROM updates";
  $mysqli_stmt = $mysqli->prepare($qry);
  $mysqli_stmt->execute();
  $result = $mysqli_stmt->get_result();
  while ($row = $result -> fetch_assoc()) { 
      $versions .= "<div class='versions'>" . $row["UPDATE_TITLE"] . "<div class='arrow'></div>";
      $versions .= "</div>";
  }
  $mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updates</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/updates.css">
</head>
<body>
    <header id='update-header'>
        <div id="header-flex">
            <a href="startmenu.php">< Back</a>
            <h1>Update history</h1>
        </div>
    </header>
    <main id='update-main'>
        <div id="version-container">
            <?php 
            echo $err;
            echo $versions;
            ?>
        </div>
        <div id="description-container">
            <div id="description-top">
                <div id="descriptionTitle"><h1></h1></div>
                <div id="descriptionDate"><p><?= $date ?></p></div>
            </div>
            <p id='updateInfo'>
            </p>
        </div>
    </main>
    <footer>
        <img id="logo" src='../img/anthonytoons.png' alt=''/>
        <div id='feedback'>
            <p>Any feedback on the updates is appreciated! I will try my best to keep the game balanced, but that's not possible on my own.</p>
        </div>
    </footer>
    <script src="../js/updates.js"></script>
    <script type='text/javascript'>getInformation(0)</script>
</body>
</html>