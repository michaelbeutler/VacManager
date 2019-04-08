<?php

use PHPMailer\PHPMailer\PHPMailer;
require '../../vendor/autoload.php';

function sendAcceptedVacationMail($email, $firstname, $lastname, $start, $end, $accepted_by) {
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Set who the message is to be sent from
        $mail->setFrom('vac-manager@iperka.com', 'Vacation Manager');
        //Set an alternative reply-to address
        $mail->addReplyTo('noreply@iperka.com', 'iperka.com');
        //Set who the message is to be sent to
        $mail->addAddress($email, $firstname . ' ' . $lastname);
        //Set the subject line
        $mail->Subject = 'Vacation request accepted';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $message = file_get_contents('../email-templates/accepted_vacation.html');
        $message = str_replace('%start%', $start, $message); 
        $message = str_replace('%end%', $end, $message);
        $message = str_replace('%accepted_by%', $accepted_by, $message); 

        $mail->msgHTML($message);
        //Replace the plain text body with one created manually
        $mail->AltBody = 'Your vacation request is marked as accepted by ' . $accepted_by;
                
        //send the message, check for errors
        if (!$mail->send()) {
                $mail->ErrorInfo;
                return false;
        } else {
                return true;
        }
}