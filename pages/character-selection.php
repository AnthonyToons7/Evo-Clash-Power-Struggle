<?php
    include '../ww.php';
    session_start();
    if (!isset($_SESSION["USERS_ID"]) || !$_SESSION["USERS_ID"]){
        header("Location:../../login.php");
    }
    $roomID = $_GET['id'];
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $err = "";
    $content = "";
	if(isset($_GET["error"])){
		$err = $_GET["error"];
	}
    $mysqli = new mysqli($one, $two, $three, $four);
    $qry = "SELECT CHARACTER_NAME, CHARACTERS_ID FROM characters";
    $result = $mysqli_stmt = $mysqli->query($qry);
    $i = 1;
    while ($row = $result -> fetch_assoc()) { 
        if ($i < 5){
            $content .= "<div class='character-selection-box'>";
            $content .= "<input type='checkbox' name='characterCheckbox[]' value='$row[CHARACTERS_ID]' class='checkbox' id='$row[CHARACTER_NAME]'/>";
            $content .= "<label for='$row[CHARACTER_NAME]'>" .  $row['CHARACTER_NAME'] . "</label>";
            $content .= "</div>";
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
    <title>Character-selection</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/characterSelect.css">
    <script src="../js/characterSelect.js"></script>
</head>
<body>
    <main>
        <form id="char_selection_form" method="POST" action="<?php echo "../game-func-pages/gameHandler.php?id=" . $roomID; ?>">
            <?php echo $content ?>
            <script type="text/javascript">
                let i = 1;
                limitCheckBoxes(2)
                i++;
            </script>
            <input type="submit" value="Ready!" id="ready">
        </form>
    </main>
</body>
</html>