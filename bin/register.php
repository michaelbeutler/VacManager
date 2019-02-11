<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

// Check if all parameters given
if (isset($_GET['inputFirstname'], $_GET['inputLastname'], $_GET['inputPassword'], $_GET['inputRepeatPassword'],  $_GET['inputStartWork'],
    $_GET['inputEndWork'], $_GET['inputClassId'], $_GET['inputEmployerId'])) {

    // Username and personal data
    $form_firstname = htmlspecialchars($_GET['inputFirstname']);
    $form_lastname = htmlspecialchars($_GET['inputLastname']);

    // Password
    $form_password = htmlspecialchars($_GET['inputPassword']);
    $form_repeat_password = htmlspecialchars($_GET['inputRepeatPassword']);

    // Work
    $form_start_work = htmlspecialchars($_GET['inputStartWork']);
    $form_end_work = htmlspecialchars($_GET['inputEndWork']);

    // Class and employer
    $form_class_id = htmlspecialchars($_GET['inputClassId']);
    $form_employer_id = htmlspecialchars($_GET['inputEmployerId']);

    include_once('dbconnect.php');
    $conn = openConnection();

    $sql = "SELECT * FROM `tbl_class` WHERE `id`=" . $_GET['inputClassId'];
    $result = $conn->query($sql);

    if ($result->num_rows < 1) {
        $sql = "INSERT INTO `tbl_class` (`id`, `class_name`, `class_longname`) VALUES (". $_GET['inputClassId'] .", '". $_GET['inputClassName'] ."', '". $_GET['inputClassLongname'] ."');";
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
            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
                
            // set parameters and execute
            $firstname = $form_firstname;
            $lastname = $form_lastname;
            $birthdate =  date("Y-m-d", strtotime($_GET['inputBirthdate']));
            $username = $_GET['inputUsername'];
            $password = hash('sha512', $form_password . $random_salt);
            $salt = $random_salt;
            $class_id = $_GET['inputClassId'];
            $employer_id = $_GET['inputEmployerId'];
            $start_work = date("Y-m-d", strtotime($_GET['inputStartWork']));
            $end_work = date("Y-m-d", strtotime($_GET['inputEndWork']));

            $sql = "SELECT * FROM `tbl_user` WHERE `username`='" . $_GET['inputUsername'] ."'";
            $result = $conn->query($sql);

            if ($result->num_rows < 1) {
                if (!$stmt->execute()) {
                    $response->code = 953;
                    $response->description = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $stmt->close();
                    $conn->close();
                    $response->code = 200;
                    $response->description = 'success';
                }
            } else {
                $response->code = 210;
                $response->description = "username already taken";
                $stmt->close();
                $conn->close();
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