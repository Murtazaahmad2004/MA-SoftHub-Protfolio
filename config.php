<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| TiDB Cloud Database Configuration
|--------------------------------------------------------------------------
*/

$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";

$username = getenv("DB_USERNAME");

$password = getenv("DB_PASSWORD");

$db = "masofthub";

$port = 4000;


/*
|--------------------------------------------------------------------------
| SSL Certificate
|--------------------------------------------------------------------------
*/

$ca_paths = [

    "/etc/pki/tls/certs/ca-bundle.crt",

    "/etc/ssl/certs/ca-certificates.crt",

    __DIR__ . "/cacert.pem"

];

$cert_path = "";

foreach ($ca_paths as $path) {

    if (file_exists($path)) {

        $cert_path = $path;

        break;

    }

}


/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
*/

try {

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

    $options = [

        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        PDO::ATTR_EMULATE_PREPARES => false

    ];

    if (!empty($cert_path)) {

        if (defined("Pdo\Mysql::ATTR_SSL_CA")) {

            $options[\Pdo\Mysql::ATTR_SSL_CA] = $cert_path;

        } else {

            $options[PDO::MYSQL_ATTR_SSL_CA] = $cert_path;

        }

    }

    $conn = new PDO(

        $dsn,

        $username,

        $password,

        $options

    );

} catch (PDOException $e) {

    die(

        "Database Connection Failed: " .

        htmlspecialchars($e->getMessage())

    );

}


/*
|--------------------------------------------------------------------------
| Flash Messages
|--------------------------------------------------------------------------
*/

if (!function_exists("setFlash")) {

    function setFlash(

        string $message,

        string $type = "success"

    ): void {

        $_SESSION["flash"] = [

            "message" => $message,

            "type" => $type

        ];

    }

}


if (!function_exists("getFlash")) {

    function getFlash(): ?array {

        if (isset($_SESSION["flash"])) {

            $flash = $_SESSION["flash"];

            unset($_SESSION["flash"]);

            return $flash;

        }

        return null;

    }

}

?>