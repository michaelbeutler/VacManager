<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

// Check if all parameters given
if (isset($_GET['username'], $_GET['password'])) {

    if (isset($_GET['next']) && $_GET['next'] !== "undefined") {
        $response->url = $_GET['next'];
    }

    $form_username = htmlspecialchars($_GET['username']);
    $form_password = htmlspecialchars($_GET['password']);

    include_once('dbconnect.php');
    $conn = openConnection();

    $sql = "SELECT `username` FROM `user` WHERE `username`='" . $form_username . "'";
    $result = $conn->query($sql);

    if ($result->num_rows < 1) {
        $response->code = 203;
        $response->description = 'Username and/or password incorrect.';
    } else {
        $sql = "SELECT `user`.`id` AS 'user_id', `user`.`username`, `user`.`salt`, `user`.`password`, `user`.`employer_id`, `employer`.`name`, `employer`.`shortname`, `user`.`is_banned`, `user`.`admin` FROM `user` LEFT JOIN `employer` ON `user`.`employer_id` = `employer`.`id` WHERE `username`='" . $form_username . "' LIMIT 1;";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
            $salt = $row['salt'];
            $db_password = $row['password'];
            $password = hash('sha512', $form_password . $salt);

            if ($password == $db_password) {
                if ($row['is_banned'] == 0) {
                    session_start();
                    //var_dump($row);
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_username'] = $row['username'];
                    $_SESSION['user_hash'] = $row['password'];
                    $_SESSION['user_employer_id'] = $row['employer_id'];
                    $_SESSION['employer_name'] = $row['name'];
                    $_SESSION['employer_shortname'] = $row['shortname'];
                    $_SESSION['admin'] = $row['admin'];
    
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
                $response->description = 'Username and/or password incorrect.';
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