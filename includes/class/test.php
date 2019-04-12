<?php
require('Database.php');
require('Vacation.php');
require('User.php');
require('Employer.php');

// echo open connection
$database = new Database();
echo 'open database: ' . $database->open() . '<br>';

$result = $database->select('SELECT * FROM `vacation` LIMIT 1;');

if (is_bool($result) === true) {
    echo $result;
}

$user = User::login($database, 'michael.beutler', '08fd26a2ee91615c13c8cc634e72dbe56db307a703097dbb699f333002a0acf50cc9fe89af62e0b9e36e523fcf785abe4b91c6b6a0b4ed3f2ac8e3a7831bbc5f');
if (is_bool($user) && !$user) {
    echo 'wrong credentials';
} else {
    echo $user->to_json();
}


$vacation = Vacation::construct_mysql($result);
echo $vacation->title . '<br>';

$vacation = Vacation::construct_id($database, 12);
echo $vacation->title . '<br>';

echo $vacation->to_json();

//if ($result->num_rows < 1) {





echo 'close database: ' . $database->close() . '<br>';
