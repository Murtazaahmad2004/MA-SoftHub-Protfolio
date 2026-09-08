<?php
require_once "config.php";
require_once __DIR__ . "/vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --------- SUBSCRIBE USER ---------
if(isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Check email is valid or invalid
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash("Invalid Email Address", "danger");
        header("Location: index.php");
        exit();
    }

    try {
        // Check if email already exists in the database
        $stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $stmt->execute([$email]);
        $exists = $stmt->fetchColumn(); 

        if($exists){
            setFlash("Email already subscribed", "danger");
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES(?)");
            
            if($stmt_insert->execute([$email])) {
                
                // Send Welcome Email using PHPMailer
                $mailSent = sendMail(
                    [$email],
                    "Subscription Successful!",
                    "Thank you for subscribing to our newsletter!<br><br> We're excited to have you on board.<br><br> Stay tuned for the latest updates and exclusive offers.<br><br> Best Regards,<br><br>M.A SoftHub Team"
                );

                if($mailSent) {
                    setFlash("Subscribed Successfully!", "success");
                } else {
                    setFlash("Subscribed Successfully! (But welcome email could not be sent)", "warning");
                }

            } else {
                setFlash("Subscription Failed. Please try again.", "danger");
            }
        }
    } catch (PDOException $e) {
        setFlash("Database Error: " . $e->getMessage(), "danger");
    }

    header("Location: index.php");
    exit();
}

// --------- COMMON MAIL FUNCTION ---------
function sendMail($recipients, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;

        $mail->Username = getenv('MAIL_USERNAME');
        $mail->Password = getenv('MAIL_PASSWORD');

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom(
            getenv('MAIL_USERNAME') ?: 'masofthub@gmail.com',
            'M.A SoftHub'
        );

        foreach($recipients as $r) {
            $mail->addAddress($r);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();

    } catch(Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }
}

// --------- NOTIFY ALL SUBSCRIBERS ---------
function notifySubscribers($changeType, $item) {
    global $conn;

    try {
        $stmt = $conn->query("SELECT email FROM newsletter_subscribers");
        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($subscribers) {
            $emailList = [];
            foreach($subscribers as $s) {
                $emailList[] = $s['email'];
            }

            $body = "
            <h3>Portfolio $changeType</h3>
            <p><b>Title:</b> {$item['title']}</p>
            <p><b>Description:</b> {$item['description']}</p>
            ";

            sendMail($emailList, "Portfolio $changeType: {$item['title']}", $body);
        }
    } catch (PDOException $e) {
        error_log("Notify Error: " . $e->getMessage());
    }
}
?>