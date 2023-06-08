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
  $newNickname = $_POST['new-username'];
  $newEmail = $_POST['new-email'];
  $newPassword = $_POST['new-password'];
  $mysqli = new mysqli($one, $two, $three, $four);
  $qry = "UPDATE users SET USER_NICKNAME = ?, USER_EMAIL = ?, USERS_PASSWORD = ? WHERE USERS_ID = ?";
  $mysqli_stmt = $mysqli->prepare($qry);
  $mysqli_stmt -> bind_param("sssi", $newNickname, $newEmail, $newPassword, $_SESSION['USERS_ID']);
  $mysqli_stmt->execute();
  $result = $mysqli_stmt->get_result();
  $mysqli->close();
  header("Location: account.php");