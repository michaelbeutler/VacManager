<?php
require('../class/Autoload.php')

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

// Check if all parameters given
if (isset($_GET['username'], $_GET['password'])) {

    if (isset($_GET['next']) && $_GET['next'] !== "undefined") {
        $response->url = $_GET['next'];
    }

    $username = htmlspecialchars($_GET['username']);
    $password = htmlspecialchars($_GET['password']);

    if ($User::login($database, $username, $password)) {
        $response->code = 200;
        $response->description = 'success';
    } else {
        $response->code = 203;
        $response->description = 'Username or password not correct!';
    }

} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}

echo json_encode($response);
