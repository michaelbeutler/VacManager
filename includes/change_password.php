<?php
require('check_login.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

if (!check_login()) {
    header("Location: ./login.html");
    die();
} else {
    if (isset($_GET['password'], $_GET['repeat'])) {
        if ($_GET['password'] == $_GET['repeat']) {
            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

            $password = hash('sha512', $_GET['password'] . $random_salt);

            include_once('dbconnect.php');
            $conn = openConnection();

            if (!$stmt = $conn->prepare("UPDATE `user` SET `password`=?, `salt`=? WHERE `id`=". $_SESSION['user_id'])) {
                $response->code = 951;
                $response->description = "prepare failed: (" . $conn->errno . ") " . $conn->error;
            } else {
                if (!$stmt->bind_param("ss", $password, $random_salt)) {
                    $response->code = 952;
                    $response->description = "binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    if (!$stmt->execute()) {
                        $response->code = 953;
                        $response->description = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                        $stmt->close();
                        $conn->close();
                        $response->code = 200;
                        $response->description = 'success';
                        session_destroy();
                    }   
                }
            }
        } else {
            $response->code = 905;
            $response->description = 'passwords do not match';
        }
    } else {
        // Parameters missing
        $response->code = 900;
        $response->description = 'parameters missing';
    }  
}

echo json_encode($response);
?>