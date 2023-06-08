<?php
include '../ww.php';
session_start();
if ($_SESSION["USERS_ID"] != true){
    header("Location:../../login.php");
}
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  $err = "";
  $content = "";
  if(isset($_GET["error"])){
      $err = $_GET["error"];
  }
  $mysqli = new mysqli($one, $two, $three, $four);
  $qry = "SELECT USER_NICKNAME, USER_EMAIL FROM users WHERE USERS_ID = ?";
  $mysqli_stmt = $mysqli->prepare($qry);
  $mysqli_stmt -> bind_param("i", $_SESSION['USERS_ID']);
  $mysqli_stmt->execute();
  $result = $mysqli_stmt->get_result();
  while ($row = $result -> fetch_assoc()) { 
      $content .= "<div>";
      $content .= "<h1>Username</h1>";
      $content .= "<div id='user-nickname'>" . $row["USER_NICKNAME"] . "</div>&nbsp;";
      $content .= "<h1>Email</h1>";
      $content .= "<div id='user-email'>" . $row["USER_EMAIL"] . "</div>&nbsp;";
      $content .= "</div>";
      $content .= "<div>";
      $content .= "<a href='update.php' id='update-btn' class='account-btns'>Change information</a>&nbsp;";
      $content .= "<a href='logout.php' id='logout-btn' class='account-btns'>Logout</a>";
      $content .= "</div>";
      $content .= "<div id='delete-account-container'>
      <a href='../pages/startmenu.php' id='back-btn' class='account-btns'>< Back</a>
      <a href='delete.php' id='delete-btn' class='account-btns'>Delete account</a>
      </div>";
  }
  $mysqli->close();
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
    <main class='account-main'>
      <div class="account-container">
        <h1 id='account-title'>Account</h1>
        <?php 
            echo $err;
            echo $content;
        ?>
      </div>
    </main>

  </body>
  </html>