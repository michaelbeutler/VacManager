<?php
function check_login() {
    // start session
    session_start();

    // check if user is logged in
    if (isset($_SESSION['user_id'], $_SESSION['user_username'], $_SESSION['user_hash'])) {

        // connect to database
        include_once('dbconnect.php');
        $conn = openConnection();

        // select user in database
        $sql = "SELECT * FROM `tbl_user` WHERE `id`='" . $_SESSION['user_id'] . "'";

        // check if execution was successfull
        if ($result = $conn->query($sql)) {
            // check that ther is only one account with this id
            if ($result->num_rows == 1) {
                // loop throw results
                while($row = $result->fetch_assoc()) {
                    
                    // assign variables
                    $db_password = $row['password'];
                    $password = $_SESSION['user_hash'];
                    
                    // close connection
                    $conn->close();

                    // compare password stored in session with database
                    if ($password == $db_password) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        return true;
    } else {
        return false;
    }
}
?>