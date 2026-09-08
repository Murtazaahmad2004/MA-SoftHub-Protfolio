<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Aapke TiDB Credentials
$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com"; 
$username = "3u2Rst12QNYjxiL.root";
$password = "2J3K4r2pG56zR7F4";
$db = "masofthub"; 
$port = 4000;

try {
    // Hamari apni download ki hui SSL file ka rasta
    $ssl_cert = __DIR__ . '/cacert.pem';
    
    // PHP 8.5+ Deprecation Warnings bypass karne ke liye integer codes (1012) use kiye hain
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        1012 => $ssl_cert, // 1012 = PDO::MYSQL_ATTR_SSL_CA
        1014 => false,     // 1014 = PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT
    ];

    // Connection ban raha hai
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $username, $password, $options);
    
} catch(PDOException $e) {
    die("DataBase Connection Failed: " . $e->getMessage());
}

// Flash messages functions
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