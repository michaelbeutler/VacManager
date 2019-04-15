<?php
require('../class/Autoload.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

Session::start();
$database = new Database();
if (!User::check_login($database)) {
    die('check_login failed');
}


$response->code = 200;
$response->description = 'success';
$response->data = Contingent::get_contingent($database, User::getCurrentUser($database), date('Y'));


echo json_encode($response);
