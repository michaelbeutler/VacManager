<?php
function session_error_handling_function($code, $msg, $file, $line) {
    // echo if debug
}

set_error_handler('session_error_handling_function');

require('Database.php');
require('Vacation.php');
require('Contingent.php');
require('VacationType.php');
require('Session.php');
require('User.php');
require('Employer.php');
