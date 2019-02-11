<?php
function check_login() {
    session_start();
    if (isset($_SESSION['user_id'], $_SESSION['user_username'], $_SESSION['user_hash'])) {
        include_once('dbconnect.php');
        $conn = openConnection();
        $sql = "SELECT * FROM `tbl_user` WHERE `id`='" . $_SESSION['user_id'] . "'";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $db_password = $row['password'];
            $password = $_SESSION['user_hash'];
            if ($password == $db_password) {
                return true;
            } else {
                return false;
            }
            $conn->close();
        }
        return true;
    } else {
        return false;
    }
}
?>