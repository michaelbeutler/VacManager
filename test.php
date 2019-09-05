<?php
require('includes/class/Autoload.php');
$database = new Database();
$database->update(
    'vacation',
    array('title' => 't', 'description' => 'd'),
    array('%s', '%s'),
    array('ID' => 1),
    array('%i')
);