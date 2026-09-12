<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/config.php";

require_once __DIR__ . "/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/*
|--------------------------------------------------------------------------
| Newsletter Subscription
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        setFlash("Invalid Email Address", "danger");

        header("Location: index.php#newsletter");

        exit();

    }

    try {

        /*
        |--------------------------------------------------------------------------
        | Check Existing Email
        |--------------------------------------------------------------------------
        */

        $stmt = $conn->prepare(

            "SELECT id FROM newsletter_subscribers WHERE email = ? LIMIT 1"

        );

        $stmt->execute([$email]);

        $exists = $stmt->fetchColumn();


        if ($exists) {

            setFlash(

                "This email is already subscribed.",

                "warning"

            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Insert Email
            |--------------------------------------------------------------------------
            */

            $stmt_insert = $conn->prepare(

                "INSERT INTO newsletter_subscribers (email) VALUES (?)"

            );

            $stmt_insert->execute([$email]);


            /*
            |--------------------------------------------------------------------------
            | Send Welcome Email
            |--------------------------------------------------------------------------
            */

            $mailSent = sendMail(

                [$email],

                "Subscription Successful! - M.A SoftHub",

                "

                <div style='font-family: Arial, sans-serif;'>

                    <h2>Thank You for Subscribing!</h2>

                    <p>

                        Thank you for subscribing to the M.A SoftHub newsletter.

                    </p>

                    <p>

                        We're excited to have you on board.

                    </p>

                    <p>

                        Stay tuned for the latest updates and exclusive offers.

                    </p>

                    <br>

                    <p>Best Regards,</p>

                    <p><b>M.A SoftHub Team</b></p>

                </div>

                "

            );


            if ($mailSent) {

                setFlash(

                    "Subscribed Successfully! Welcome email sent.",

                    "success"

                );

            } else {

                setFlash(

                    "Subscribed Successfully! But welcome email failed to send.",

                    "warning"

                );

            }

        }

    } catch (PDOException $e) {

        error_log(

            "Newsletter Database Error: " .

            $e->getMessage()

        );

        setFlash(

            "Database Error: " . $e->getMessage(),

            "danger"

        );

    }

    header("Location: index.php#newsletter");

    exit();

}


/*
|--------------------------------------------------------------------------
| Common Mail Function
|--------------------------------------------------------------------------
*/

function sendMail(

    array $recipients,

    string $subject,

    string $body

): bool {

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();

        $mail->Host = "smtp.gmail.com";

        $mail->SMTPAuth = true;


        $mailUsername = getenv("MAIL_USERNAME");

        $mailPassword = getenv("MAIL_PASSWORD");


        if (empty($mailUsername) || empty($mailPassword)) {

            error_log(

                "Mail Error: MAIL_USERNAME or MAIL_PASSWORD is missing."

            );

            return false;

        }


        $mail->Username = $mailUsername;

        $mail->Password = $mailPassword;


        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        $mail->Port = 465;

        $mail->Timeout = 20;


        $mail->setFrom(

            $mailUsername,

            "M.A SoftHub"

        );


        foreach ($recipients as $recipient) {

            $mail->addAddress($recipient);

        }


        $mail->isHTML(true);

        $mail->CharSet = "UTF-8";

        $mail->Subject = $subject;

        $mail->Body = $body;


        return $mail->send();

    } catch (Exception $e) {

        error_log(

            "PHPMailer Error: " .

            $mail->ErrorInfo

        );

        return false;

    }

}


/*
|--------------------------------------------------------------------------
| Notify All Subscribers
|--------------------------------------------------------------------------
*/

function notifySubscribers(

    string $changeType,

    array $item

): void {

    global $conn;

    try {

        $stmt = $conn->query(

            "SELECT email FROM newsletter_subscribers"

        );

        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if (!$subscribers) {

            return;

        }


        $emailList = [];


        foreach ($subscribers as $subscriber) {

            $emailList[] = $subscriber["email"];

        }


        $title = htmlspecialchars($item["title"] ?? "");

        $description = htmlspecialchars($item["description"] ?? "");


        $body = "

        <h3>Portfolio $changeType</h3>

        <p><b>Title:</b> $title</p>

        <p><b>Description:</b> $description</p>

        ";


        sendMail(

            $emailList,

            "Portfolio $changeType: " . ($item["title"] ?? ""),

            $body

        );

    } catch (PDOException $e) {

        error_log(

            "Notify Error: " . $e->getMessage()

        );

    }

}

?>