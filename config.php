<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql12.freesqldatabase.com";
$username = "sql12835715";
$password = "tksj3KCVJp";
$db = "sql12835715";
$port = 3306;

$conn = new mysqli($host, $username, $password, $db, $port);
if($conn->connect_error){
    die("DataBase Connection Failed" . $conn->connect_error);
}

if(!function_exists("setFlash")){
    function setFlash($message,$type='success'){
        $_SESSION['flash'] = ["message" => $message, "type" => $type];
    }
}

if(!function_exists("getFlash")){
    function getFlash(){
        if(isset($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
    }
}

?>