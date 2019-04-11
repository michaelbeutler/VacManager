<?php
require('check_login.php');
if (!check_login()) {
    header("Location: login.html");
    die();
}

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

// Check if all parameters given
if (isset($_GET['title'], $_GET['start'], $_GET['end'], $_GET['numDays'],  $_GET['vacType'])) {

    // Username and personal data
    $title = htmlspecialchars($_GET['title']);
    $start = htmlspecialchars($_GET['start']);
    $end = htmlspecialchars($_GET['end']);
    $numDays = htmlspecialchars($_GET['numDays']);
    $vacType = htmlspecialchars($_GET['vacType']);

    if (empty($title) || empty($numDays)) {
        $response->code = 901;
        $response->description = 'parameters missing or empty';
    } else {
        include_once('dbconnect.php');
        $conn = openConnection();
    
        // prepare and bind
        if (!$stmt = $conn->prepare("INSERT INTO `vacation` (`start`, `end`, `days`, `title`, `user_id`, `vacation_type_id`) VALUES (?, ?, ?, ?, ?, ?)")) {
            $response->code = 951;
            $response->description = "prepare failed: (" . $conn->errno . ") " . $conn->error;
        } else {
            if (!$stmt->bind_param("ssdsii", $bstart, $bend, $bnumDays, $btitle, $userId, $bvacType)) {
                $response->code = 952;
                $response->description = "binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                // set parameters and execute
                $bstart = $start;
                $bend = $end;
                $bnumDays =  $numDays;
                $btitle = $title;
                $userId = $_SESSION['user_id'];
                $bvacType = $vacType;
                
                if (!$stmt->execute()) {
                    $response->code = 953;
                    $response->description = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $stmt->close();
                    $conn->close();
                    $response->code = 200;
                    $response->description = 'success';
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
