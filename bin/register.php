<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

// Check if all parameters given
if (isset(
    $_GET['firstname'], $_GET['lastname'], 
    $_GET['birthdate'], $_GET['username'], 
    $_GET['password'], $_GET['repeat'], 
    $_GET['startWork'], $_GET['endWork'], 
    $_GET['classId'], $_GET['classLongName'], 
    $_GET['className'], $_GET['employerId'], 
    $_GET['vacDays']
    )) {

    // personal data
    $firstname = htmlspecialchars($_GET['firstname']);
    $lastname = htmlspecialchars($_GET['lastname']);
    $birthdate = htmlspecialchars($_GET['birthdate']);
    
    // credentials
    $username = htmlspecialchars($_GET['username']);
    $password = htmlspecialchars($_GET['password']);
    $repeat = htmlspecialchars($_GET['repeat']);

    if ($password !== $repeat) {
        $response->code = 901;
        $response->description = 'passwords dont match';
    } else {
        // employer
        $start_work = htmlspecialchars($_GET['startWork']);
        $end_work = htmlspecialchars($_GET['endWork']);
        $vac_days = htmlspecialchars($_GET['vacDays']);
        $employer_id = htmlspecialchars($_GET['employerId']);

        // class
        $class_id = htmlspecialchars($_GET['classId']);
        $class_long_name = htmlspecialchars($_GET['classLongName']);
        $class_name = htmlspecialchars($_GET['className']);

        // open db connection
        include_once('dbconnect.php');
        $conn = openConnection();

        // check if class already exists
        $sql = "SELECT * FROM `tbl_class` WHERE `id`=" . $class_id;
        $result = $conn->query($sql);

        if ($result->num_rows < 1) {
            // if not, insert
            $sql = "INSERT INTO `tbl_class` (`id`, `class_name`, `class_longname`) VALUES (". $class_id .", '". $class_name ."', '". $class_long_name ."');";
            $conn->query($sql);
        }

        // prepare and bind
        if (!$stmt = $conn->prepare("INSERT INTO `tbl_user` (`firstname`, `lastname`, `birthdate`, `username`, `password`, `salt`, `tbl_class_id`, `tbl_employer_id`, `start_work`, `end_work`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
            $response->code = 951;
            $response->description = "prepare failed: (" . $conn->errno . ") " . $conn->error;
        } else {
            if (!$stmt->bind_param("ssssssiiss", $firstname, $lastname, $birthdate, $username, $password, $salt, $class_id, $employer_id, $start_work, $end_work)) {
                $response->code = 952;
                $response->description = "binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                // Generate random salt
                $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
                    
                // set parameters and execute
                $birthdate =  date("Y-m-d", strtotime($birthdate));
                $password = hash('sha512', $password . $salt);
                $start_work = date("Y-m-d", strtotime($start_work));
                $end_work = date("Y-m-d", strtotime($start_work));

                // get accounts with same username
                $sql = "SELECT * FROM `tbl_user` WHERE `username`='" . $username ."'";
                $result = $conn->query($sql);

                // check if username already exists
                if ($result->num_rows < 1) {
                    if (!$stmt->execute()) {
                        // error while executing query
                        $response->code = 953;
                        $response->description = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                        // get user id
                        $sql = "SELECT `id`, `username` FROM `tbl_user` WHERE `username`='" . $username ."'";
                        if (!$result = $conn->query($sql)) {
                            // error while executing query
                            $response->code = 953;
                            $response->description = "Execute failed: (" . $conn->errno . ") " . $conn->error;
                        } else {
                            if ($result->num_rows == 1) {
                                // create contingent
                                $row = $result->fetch_assoc();
                                $sql = "INSERT INTO `tbl_contingent` (`year`, `basis`, `tbl_user_id`) VALUES (" . date('Y') . "," . $vac_days . "," . $row['id'] . ")";
                                $result = $conn->query($sql);
                                
                            }
                            $stmt->close();
                            $conn->close();
                            $response->code = 200;
                            $response->description = 'success';
                        }
                    }
                } else {
                    $response->code = 210;
                    $response->description = "username already taken";
                    $stmt->close();
                    $conn->close();
                }
                
            }
        }
    }

   
} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}

echo json_encode($response);

?>