<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// SQLite Database Connection (PDO)
try {
    // Yeh script ke folder mein hi 'database.sqlite' file bana dega
    $conn = new PDO('sqlite:' . __DIR__ . '/masofthub.db');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Automatically create tables if they don't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS portfolio_items (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, description TEXT)");
    $conn->exec("CREATE TABLE IF NOT EXISTS contact_messages (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT, phone TEXT, message TEXT)");
    $conn->exec("CREATE TABLE IF NOT EXISTS newsletter_subscribers (id INTEGER PRIMARY KEY AUTOINCREMENT, email TEXT UNIQUE)");

} catch (PDOException $e) {
    die("DataBase Connection Failed: " . $e->getMessage());
}

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