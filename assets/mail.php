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
        if ( empty($name) OR empty($subject) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            throw new Exception("Please complete the form and try again.");
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


    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.


        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $recipient = "info@yoursite.com";

        // Set the email subject.
        $subject = "New contact from $name";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Subject: $subject\n\n";
        $email_content .= "Message:\n$message\n";

        // Build the email headers.
        $email_headers = "From: $name <$email>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>
