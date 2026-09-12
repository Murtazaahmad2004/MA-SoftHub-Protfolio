<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method");
}

$email = trim($_POST["email"] ?? "");

if (empty($email)) {
    die("Email field empty");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address");
}

try {

    $sql = "INSERT INTO newsletter_subscribers (email)
            VALUES (:email)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":email" => $email
    ]);

    echo "Email database mein successfully save ho gayi.";

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        echo "Ye email pehle se database mein mojood hai.";
    } else {
        echo "Database error: " . $e->getMessage();
    }

}