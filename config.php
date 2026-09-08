<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Apni TiDB Cloud ki details yahan dalein
$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$username = "3u2Rst12QNYjxiL.root";
$password = "2J3K4r2pG56zR7F4";
$db = "sql12835715";
$port = 4000;

try {
    // TiDB Serverless requires SSL, isliye yeh options zaroori hain
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, 
    ];

    // PDO Connection for TiDB (MySQL driver hi use hoga)
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $username, $password, $options);
    
    // Automatically tables create karne ki command (agar pehle se nahi bani hui)
    $conn->exec("CREATE TABLE IF NOT EXISTS portfolio_items (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255), description TEXT)");
    $conn->exec("CREATE TABLE IF NOT EXISTS contact_messages (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), email VARCHAR(255), phone VARCHAR(50), message TEXT)");
    $conn->exec("CREATE TABLE IF NOT EXISTS newsletter_subscribers (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) UNIQUE)");

} catch(PDOException $e) {
    die("DataBase Connection Failed: " . $e->getMessage());
}

// Flash messages function[cite: 2]
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