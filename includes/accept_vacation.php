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
        include_once('mail.php');

        $sql = "SELECT * FROM `vacation` LEFT JOIN `user` ON `vacation`.`user_id` = `user`.`user_id` WHERE `id`=". $_GET['request_id'] .";";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if (sendAcceptedVacationMail($row['email'], $row['firstname'], $row['lastname'], $row['start'], $row['end'], $_SESSION['user_username'])) {
                    $response->code = 200;
                    $response->description = 'success';
                } else {
                    $response->code = 201;
                    $response->description = 'cant send mail';
                }
            }
        }
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