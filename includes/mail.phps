<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Set who the message is to be sent from
$mail->setFrom('vac-manager@iperka.com', 'Vacation Manager');
//Set an alternative reply-to address
$mail->addReplyTo('noreply@iperka.com', 'iperka.com');
//Set who the message is to be sent to
$mail->addAddress('michael.beutler@gibmit.ch', 'Michael Beutler');
//Set the subject line
$mail->Subject = 'Vacation request accepted';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('../email-templates/accepted_vacation.html'), __DIR__);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

//send the message, check for errors
if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
} else {
        echo "Message sent!";
}