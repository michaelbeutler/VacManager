<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

require('./class/Autoload.php');

// Check if all parameters given
if (isset($_GET['firstname'], $_GET['lastname'],
$_GET['username'], $_GET['email'],
$_GET['password'], $_GET['repeat'],
$_GET['employerId'],
$_GET['vacDays'])) {

    // personal data
    $firstname = trim(htmlspecialchars($_GET['firstname']));
    $lastname = trim(htmlspecialchars($_GET['lastname']));

    // credentials
    $username = preg_replace('/\s+/', ' ', trim(htmlspecialchars($_GET['username'])));
    $email = trim(htmlspecialchars($_GET['email']));
    $password = trim(htmlspecialchars($_GET['password']));
    $repeat = trim(htmlspecialchars($_GET['repeat']));

    if (strlen($firstname) > 45 || strlen($firstname) < 2) {
        $response->code = 902;
        $response->description = 'firstname length invalid';
        echo json_encode($response);
        die();
    }

    if (strlen($lastname) > 45 || strlen($lastname) < 2) {
        $response->code = 902;
        $response->description = 'lastname length invalid';
        echo json_encode($response);
        die();
    }

    if (strlen($username) > 91 || strlen($username) < 3) {
        $response->code = 902;
        $response->description = 'username length invalid';
        echo json_encode($response);
        die();
    }

    if (strlen($email) > 45 || strlen($firstname) < 6) {
        $response->code = 902;
        $response->description = 'email length invalid';
        echo json_encode($response);
        die();
    }

    if ($password !== $repeat) {
        $response->code = 901;
        $response->description = 'passwords dont match';
    } else {
        // employer
        $vac_days = htmlspecialchars($_GET['vacDays']);
        $employer_id = htmlspecialchars($_GET['employerId']);

        // open db connection
        $database = new Database();

        // check if user already exiists
        $result = $database->select("SELECT * FROM `user` WHERE `username`='" . $username . "' OR `email`='" . $email . "';");
        if ($result->num_rows < 1) {
            // Generate random salt
            $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

            // set parameters and execute
            $password = hash('sha512', $password . $salt);

            $result = $database->insert("user", array('firstname' => $firstname, 'lastname' => $lastname, 'username' => $username, 'email' => $email, 'password' => $password, 'salt' => $salt, 'employer_id' => $employer_id), array('%s', '%s', '%s', '%s', '%s', '%s', '%i'));
            if ($result) {
                $result = $database->select("SELECT * FROM `user` WHERE `username`='" . $username . "' OR `email`='" . $email . "' LIMIT 1;");
                $row = $result->fetch_assoc();
                $database->insert("contingent", array('year' => date('Y'), 'contingent' => $vac_days, 'user_id' => $row['id']), array('%s', '%d', '%i'));

                $response->code = 200;
                $response->description = 'success';
            } else {
                $response->code = 953;
                $response->description = "Execute failed";
            }
        } else {
            $response->code = 210;
            $response->description = "username already taken";
        }
    }
} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}

echo json_encode($response);
