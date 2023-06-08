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
    $mysqli = new mysqli($one, $two, $three, $four);
    $qry = "SELECT * FROM updates WHERE UPDATE_ID = ?;";
    $mysqli_stmt = $mysqli->prepare($qry);
    $mysqli_stmt->bind_param('i', $_GET['update']);
    $mysqli_stmt->execute();
    $result = $mysqli_stmt->get_result();
    $row = $result->fetch_assoc();

    $data = [
        'updates' => [
            "desc" => $row['UPDATE_DESC']
        ]
    ];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);

    