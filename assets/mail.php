<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        $firstname = strip_tags(trim($_POST["firstname"]));
        $firstname = str_replace(array("\r","\n"),array(" "," "),$firstname);
        $lastname = strip_tags(trim($_POST["lastname"]));
        $lastname = str_replace(array("\r","\n"),array(" "," "),$lastname);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $subject = trim($_POST["subject"]);
        $company = trim($_POST["company"]);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($firstname) OR empty($lastname) OR empty($company) OR empty($subject) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo "Please complete the form and try again.";
            exit;
        }
        //Recipients
        $mail->setFrom($email, $firstname." ".$lastname);
        $mail->addAddress("Aly@jafferbhoymeheralli.com");
        $mail->addCC('Jafferm64@hotmail.com');
        $mail->addCC('bilal@obsidianmedia.io');

        //Content
        $mail->isHTML(false);
        $mail->Subject = 'Contact Form: '.$firstname." ".$lastname;
        // Build the email content.
        $email_content = "Name: $firstname $lastname\r";
        $email_content .= "Company: $company\r";
        $email_content .= "Email: $email\r\r";
        $email_content .= "Subject: $subject\r\r";
        $email_content .= "Message:\r$message\r";

        $mail->Body = $email_content;
        $mail->send();

        http_response_code(200);
        echo 'Thank You! Your message has been sent.';

    }
    catch (Exception $e)
    {
        http_response_code(500);
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
else
{
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>
