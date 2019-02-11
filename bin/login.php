<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

// Check if all parameters given
if (isset($_GET['username'], $_GET['password'])) {

    $form_username = htmlspecialchars($_GET['username']);
    $form_password = htmlspecialchars($_GET['password']);


    include_once('dbconnect.php');
    $conn = openConnection();

    $sql = "SELECT `username` FROM `tbl_user` WHERE `username`='" . $form_username . "'";
    $result = $conn->query($sql);

    if ($result->num_rows < 1) {
        $response->code = 204;
        $response->description = "no account with username <" . $form_username . ">";
    } else {
        $sql = "SELECT `tbl_user`.`id` AS 'UID', `tbl_user`.`username`, `tbl_user`.`salt`, `tbl_user`.`password`, `tbl_user`.`tbl_class_id`, `tbl_user`.`tbl_employer_id`, `tbl_employer`.`id` AS 'EID', `tbl_employer`.`name`, `tbl_employer`.`shortname`, `tbl_user`.`ban` FROM `tbl_user` LEFT JOIN `tbl_employer` ON `tbl_user`.`tbl_employer_id` = `tbl_employer`.`id` WHERE `username`='" . $form_username . "'";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
            $salt = $row['salt'];
            $db_password = $row['password'];
            $password = hash('sha512', $form_password . $salt);

            if ($password == $db_password) {
                if ($row['ban'] == 0) {
                    session_start();
                    //var_dump($row);
                    $_SESSION['user_id'] = $row['UID'];
                    $_SESSION['user_username'] = $row['username'];
                    $_SESSION['user_hash'] = $row['password'];
                    $_SESSION['user_class'] = $row['tbl_class_id'];
                    $_SESSION['user_employer_id'] = $row['tbl_employer_id'];
                    $_SESSION['employer_name'] = $row['name'];
                    $_SESSION['employer_shortname'] = $row['shortname'];
    
                    // success
                    $response->code = 200;
                    $response->description = 'login success';
                } else {
                    $response->code = 205;
                    $response->description = 'Your account has been banned!';
                }    
            } else {
                // wrong password
                $response->code = 203;
                $response->description = 'login invalid';
                $response->debug = $db_password . '</>' . $password . '</>' . $salt;
            }
        }
    }

    $conn->close();

} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}

echo json_encode($response);

?>