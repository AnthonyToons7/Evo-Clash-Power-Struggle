<?php 
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (isset($_POST["submit"])) {
    $mysqli = new mysqli("localhost", "root", "root", "rpg_full", "3306");
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $victories = 0;
    if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        $qry = "INSERT INTO users (USER_NICKNAME, USER_EMAIL, USERS_PASSWORD, USER_VICTORIES) VALUES (?, ?, ?, ?);";
        $mysqli_stmt = $mysqli->prepare($qry);
        $mysqli_stmt->bind_param('ssss', $username, $email, $password, $victories);
        $mysqli_stmt->execute();

        $result = $mysqli_stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["USERS_ID"] = true;
            header('Location: ../pages/startmenu.php');
        }
        else {
            $_SESSION["USERS_ID"] = false;
            header('Location: ../login.php');
        }
    }
}