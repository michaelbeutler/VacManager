<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

include_once('dbconnect.php');
$conn = openConnection();



echo json_encode($response);
$conn->close();
?>