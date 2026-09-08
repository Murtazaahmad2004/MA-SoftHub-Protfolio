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

// Vercel / Cloud server ke andar built-in SSL certificates ka path dhoondna
$ca_paths = [
    '/etc/ssl/certs/ca-certificates.crt', // Vercel / Debian / Ubuntu
    '/etc/pki/tls/certs/ca-bundle.crt',   // Amazon Linux
    '/usr/local/etc/openssl/cert.pem'     // Custom environments
];

$ssl_ca = '';
foreach ($ca_paths as $path) {
    if (file_exists($path)) {
        $ssl_ca = $path;
        break;
    }
}

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    
    // Agar server par SSL file mil jaye toh usay secure connection ke liye use karein
    if ($ssl_ca) {
        $options[1012] = $ssl_ca;  // 1012 = SSL_CA (Path dena zaroori tha, "true" nahi)
    }
    
    $options[1014] = false; // 1014 = SSL_VERIFY_SERVER_CERT (Strict verification bypass)

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