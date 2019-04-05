<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

include_once('dbconnect.php');
$conn = openConnection();

$sql = "SELECT * FROM `vacation_type`;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    $results = array();
    while($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    $response->code = 200;
    $response->description = 'success';
    $response->types = $results;
} else {
    // 0 results
    $response->code = 201;
    $response->description = 'no types found (create new)';
    $response->types = null;
}

echo json_encode($response);
$conn->close();

?>