<?php
include '../ww.php';
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
$err = "";
$content = "";
if(isset($_GET["error"])){
    $err = $_GET["error"];
}
$charid = $_GET['characterid'];
$mysqli = new mysqli($one, $two, $three, $four);
$qry = "SELECT CHARACTER_HP FROM characters WHERE CHARACTERS_ID = ?;";
$mysqli_stmt = $mysqli->prepare($qry);
$mysqli_stmt->bind_param('i', $charid);
$mysqli_stmt->execute();
$result = $mysqli_stmt->get_result();
$row = $result->fetch_assoc();

if(empty($row['CHARACTER_HP'])) {
    $data = [
        'error' => 'No rows found',
    ];
} else {
    $data = [
        'hp' => $row['CHARACTER_HP'],
    ];
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

    