<?php
require('check_login.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

if (!check_login()) {
    header("Location: ./login.html");
    die();
} else {
    if (isset($_GET['option'])) {

        $option = htmlspecialchars($_GET['option']);

        if ($option !== "loadClassEvents") {
            $response = (object)array();
            $response->code = 450;
            $response->description = 'not allowed';
        } else {
            if (isset($_GET['value'])) {
                $value = htmlspecialchars($_GET['value']);
    
                include_once('dbconnect.php');
                $conn = openConnection();
    
            if (!$stmt = $conn->prepare("UPDATE `user` SET `". $option ."`=? WHERE `id`=". $_SESSION['user_id'])) {
                    $response->code = 951;
                    $response->description = "prepare failed: (" . $conn->errno . ") " . $conn->error;
                } else {
                    if (!$stmt->includesd_param("i", $value)) {
                        $response->code = 952;
                        $response->description = "includesding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                        if (!$stmt->execute()) {
                            $response->code = 953;
                            $response->description = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        } else {
                            $stmt->close();
                            $conn->close();
                            $response->code = 200;
                            $response->description = 'success';
                            $response->value = $value;
                            $_SESSION[$option] = $value;
                        }   
                    }
                }
            } else {
                $sql = "SELECT `". $option ."` FROM `user`;";
                $result = $conn->query($sql);
    
                if ($result->num_rows > 0) {
                    // output data of each row
                    $results = array();
                    while($row = $result->fetch_assoc()) {
                        $results[] = $row;
                    }
    
                    $response->code = 200;
                    $response->description = 'success';
                    $response->option = $results;
                } else {
                    // 0 results
                    $response->code = 201;
                    $response->description = 'no options found (create new)';
                    $response->option = null;
                }
            }
        }   
    } else {
        // Parameters missing
        $response->code = 900;
        $response->description = 'parameters missing';
    }  
}

echo json_encode($response);
?>