<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

require('check_login.php');
require('check_employer_privileges.php');
if (!check_login() || !check_employer_privileges($_SESSION['user_employer_id'], new Priv(Priv::CAN_ACCEPT))) {
    $response->code = 403;
    $response->description = 'not allowed';
    echo json_encode($response);
    die();
}

if (isset($_GET['request_id'])) {

    include_once('dbconnect.php');
    $conn = openConnection();

    $sql = "UPDATE `vacation` SET `accepted`=1, `user_id_accepted`=". $_SESSION['user_id'] ." WHERE `id`=". $_GET['request_id'] .";";
    $result = $conn->query($sql);

    if ($conn->query($sql)) {
        $response->code = 200;
        $response->description = 'success';

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
    } else {
        // error while executing query
        $response->code = 953;
        $response->description = "Execute failed";
    }
    $conn->close();

} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}
echo json_encode($response);
?>