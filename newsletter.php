<?php
require_once "config.php";
require_once __DIR__ . "/vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['email'])) {
    $email = trim($_POST['email']);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("ERROR: Invalid Email Address format!");
    }

    try {
        // 1. Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $stmt->execute([$email]);
        $exists = $stmt->fetchColumn(); 

        if($exists){
            echo "NOTICE: Yeh email pehle se subscribed hai!";
            exit();
        }

        // 2. Insert into database
        $stmt_insert = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
        
        if($stmt_insert->execute([$email])) {
            echo "SUCCESS: Email database mein kamiyabi ke sath save ho gayi hai! 🎉";
            exit();
        } else {
            echo "FAILED: Query execute nahi ho saki.";
            exit();
        }

    } catch (PDOException $e) {
        // Agar database ka koi error hoga toh wo yahan screen par dikh jayega
        echo "DATABASE EXCEPTION ERROR: " . $e->getMessage();
        exit();
    }
} else {
    echo "Form se data post nahi hua.";
}
?>