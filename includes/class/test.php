<?php
require('Autoload.php');

Session::start();
echo User::construct_id(new Database(), $_SESSION['user_id'])->admin;