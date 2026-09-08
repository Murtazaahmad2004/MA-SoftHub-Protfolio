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

// Vercel aur Local SSL dono ke paths
$ca_paths = [
    '/etc/pki/tls/certs/ca-bundle.crt',   // Vercel (AWS Lambda)
    '/etc/ssl/certs/ca-certificates.crt', // Vercel (Debian)
    __DIR__ . '/cacert.pem'               // Aapki custom file
];

$cert_path = '';
foreach ($ca_paths as $path) {
    if (file_exists($path)) {
        $cert_path = $path;
        break;
    }
}

try {
    // PHP 8.5+ Support: Agar naya constant available hai toh wo use karega warna purana
    $ssl_ca_constant = defined('Pdo\Mysql::ATTR_SSL_CA') ? \Pdo\Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA;

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        $ssl_ca_constant => $cert_path // Sirf path dena hai, verification ko false nahi karna!
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