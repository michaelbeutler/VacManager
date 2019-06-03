<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

require('./class/Autoload.php');
$database = new Database();

Session::start();
if (!User::check_login(new Database())) {
    header("Location: login.html");
    die();
} else {
    if (isset($_GET['password'], $_GET['repeat'])) {
        if ($_GET['password'] == $_GET['repeat']) {
            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

            $password = password_hash($_GET['password'], PASSWORD_DEFAULT);

            include_once('dbconnect.php');
            $conn = openConnection();

            if (!$database->update(
                'user',
                array('password' => $password),
                array('%s'),
                array('id' => User::getCurrentUser($database)->id),
                array('%i')
            )) {
                $response->code = 951;
                $response->description = "prepare failed: (" . $conn->errno . ") " . $conn->error;
            } else {
                $response->code = 200;
                $response->description = 'success';
                Session::destroy();
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
