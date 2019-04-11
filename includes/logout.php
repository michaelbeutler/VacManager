<?php
require('check_login.php');
if (!check_login()) {
    header("Location: ../login.html");
    die();
} else {
    session_destroy();
    header("Location: ../login.html");
    die();
}
