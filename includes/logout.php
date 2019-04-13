<?php
require('./class/Autoload.php');
Session::start();
if (!User::check_login(new Database())) {
    header("Location: ../login.html");
    die();
} else {
    Session::destroy();
    header("Location: ../login.html");
    die();
}
