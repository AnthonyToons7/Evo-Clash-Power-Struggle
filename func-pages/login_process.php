<?php
include '../ww.php';
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_POST["submit"])) {
    $mysqli = new mysqli($one, $two, $three, $four, "3306");
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (emptyInputSignup($username,$email,$password) !== false){
        echo "Please fill out the correct data";
        exit();
        header("location: ../login.php");
    }
    if (invalidUsername($username) !== false){
        echo "Incorrect username";
        exit();
        header("location: ../login.php");
        
    }
    if (invalidEmail($email) !== false){
        echo "Incorrect email";
        exit();
        header("location: ../login.php");
        
    }
    if (invalidPassword($password) !== false){
        echo "Incorrect password";
        exit();
        header("location: ../login.php");
    }
    login($mysqli, $username, $email, $password);
} else {
        header("location: ../login.php");
        exit();
    }

    function emptyInputSignup($username,$email,$password) {
        if(empty($username) || empty($email) || empty($password)){
            $result = true;
        } else{
            $result = false;
        }
    return $result;
    }
    function invalidUsername($username) {
        if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
            $result = true;
        } else{
            $result = false;
        }
        return $result;
    }
    function invalidEmail($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $result = true;
        } else{
            $result = false;
        }
        return $result;
    }
    function invalidPassword($password) {
        if(!preg_match("/^[a-zA-Z0-9]*$/", $password)){
            $result = true;
        } else{
            $result = false;
        }
        return $result;
    }

function login($mysqli,$username,$email,$password)
{
    if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        $qry = "SELECT USERS_ID FROM users WHERE USER_NICKNAME= ? AND USER_EMAIL= ? AND USERS_PASSWORD= ?;";
        $mysqli_stmt = $mysqli->prepare($qry);
        $mysqli_stmt->bind_param('sss', $username, $email, $password);
        $mysqli_stmt->execute();
        
        $result = $mysqli_stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION["USERS_ID"] = $row["USERS_ID"];
            header('Location: ../pages/startmenu.php');
        }
    
        else {
            $_SESSION["USERS_ID"] = false;
            header('Location: ../login.php');
        }
    }
}