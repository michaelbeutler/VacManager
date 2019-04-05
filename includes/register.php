<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

// Check if all parameters given
if (isset(
    $_GET['firstname'], $_GET['lastname'], 
    $_GET['username'], $_GET['email'],
    $_GET['password'], $_GET['repeat'], 
    $_GET['employerId'], 
    $_GET['vacDays']
    )) {

    // personal data
    $firstname = htmlspecialchars($_GET['firstname']);
    $lastname = htmlspecialchars($_GET['lastname']);
    
    // credentials
    $username = htmlspecialchars($_GET['username']);
    $email = htmlspecialchars($_GET['email']);
    $password = htmlspecialchars($_GET['password']);
    $repeat = htmlspecialchars($_GET['repeat']);

    if ($password !== $repeat) {
        $response->code = 901;
        $response->description = 'passwords dont match';
    } else {
        // employer
        $vac_days = htmlspecialchars($_GET['vacDays']);
        $employer_id = htmlspecialchars($_GET['employerId']);

        // open db connection
        include_once('dbconnect.php');
        $conn = openConnection();

        // prepare and bind
        if (!$stmt = $conn->prepare("INSERT INTO `user` (`firstname`, `lastname`, `username`, `email`, `password`, `salt`, `employer_id`) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
            $response->code = 951;
            $response->description = "prepare failed: (" . $conn->errno . ") " . $conn->error;
        } else {
            if (!$stmt->bind_param("ssssssi", $firstname, $lastname, $username, $email, $password, $salt, $employer_id)) {
                $response->code = 952;
                $response->description = "binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                // Generate random salt
                $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
                    
                // set parameters and execute
                $password = hash('sha512', $password . $salt);

                // get accounts with same username
                $sql = "SELECT * FROM `user` WHERE `username`='" . $username ."'";
                $result = $conn->query($sql);

                // check if username already exists
                if ($result->num_rows < 1) {
                    if (!$stmt->execute()) {
                        // error while executing query
                        $response->code = 953;
                        $response->description = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                        // get user id
                        $sql = "SELECT `id`, `username` FROM `user` WHERE `username`='" . $username ."'";
                        if (!$result = $conn->query($sql)) {
                            // error while executing query
                            $response->code = 953;
                            $response->description = "Execute failed: (" . $conn->errno . ") " . $conn->error;
                        } else {
                            if ($result->num_rows == 1) {
                                // create contingent
                                $row = $result->fetch_assoc();
                                $sql = "INSERT INTO `contingent` (`year`, `contingent`, `user_id`) VALUES (" . date('Y') . "," . $vac_days . "," . $row['id'] . ")";
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