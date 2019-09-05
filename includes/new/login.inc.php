<?php
require('../class/Autoload.php');
require('./recaptcha.php');

$response = (object) array();
$response->code = 500;
$response->description = 'internal server error';

// Check if all parameters given
if (isset($_GET['username'], $_GET['password'])) {

    if (isset($_GET['next']) && $_GET['next'] !== "undefined") {
        $response->url = $_GET['next'];
    }

    $username = htmlspecialchars($_GET['username']);
    $password = htmlspecialchars($_GET['password']);
    $captcha = htmlspecialchars($_GET['token']);

    if (!check_captcha($captcha)) {
        // Parameters missing
        $response->code = 905;
        $response->description = 'captcha invalid';
        echo json_encode($response);
    }

    if (User::login(new Database(), $username, $password)) {
        // success
        $response->code = 200;
        $response->description = 'login success';
    } else {
        // wrong password
        $response->code = 203;
        $response->description = 'Username and/or password incorrect.';
    }
} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}

echo json_encode($response);
