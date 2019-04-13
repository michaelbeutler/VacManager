<?php
require('Database.php');
require('Vacation.php');
require('Session.php');
require('User.php');
require('Employer.php');

// echo open connection
$database = new Database();

//$result = $database->select('SELECT * FROM `vacation` LIMIT 1;');

/*if (is_bool($result) === true) {
    echo $result;
}*/



$login = User::login($database, 'michael.beutler', '1c22a5319c98fe6424f576757b177137d555b36da59beccc2d8ea1f75d164496685ece72b94ad11e655bd4aa13537e4d9f8263786fb6fcd7cdc980d1a9aff9c1');
if ($login) {
    echo 'login successfull<br>';
    echo Vacation::create($database, 'Test', 'Test2', '2019-04-13', '2019-04-13', 1, User::getCurrentUser($database), 1);
}

//echo User::getCurrentUser($database)->username;

//echo json_encode(Vacation::getAll($database, User::getCurrentUser($database)));

//if ($result->num_rows < 1) {





//echo 'close database: ' . $database->close() . '<br>';
