<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Aapke naye TiDB Credentials
$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com"; 
$username = "3u2Rst12QNYjxiL.root";
$password = "2J3K4r2pG56zR7F4";
$db = "masofthub"; // 'sys' ki jagah humne apna database rakha hai
$port = 4000;

try {
    // PHP 8.5+ Deprecation warnings ko fix karne ke liye direct SSL codes
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        1012 => true,   // SSL_CA
        1014 => false,  // SSL_VERIFY_SERVER_CERT
    ];

    // Connection ban raha hai
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $username, $password, $options);
    
} catch(PDOException $e) {
    die("DataBase Connection Failed: " . $e->getMessage());
}

// Flash messages functions[cite: 2]
if(!function_exists("setFlash")){
    function setFlash($message, $type='success'){
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