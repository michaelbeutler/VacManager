<?php
	include_once '../../includes/db_connect.php';
	include_once '../../includes/functions.php';

	sec_session_start();
	$query = "SELECT * FROM calendar WHERE user_id='". $_SESSION['user_id'] ."'";
	$result = $mysqli->query($query);
    while ($record = $result->fetch_assoc()) {
        $event_array[] = array(
            'id' => $record['id'],
            'title' => $record['title'],
            'start' => $record['start'],
            'end' => $record['end'],
			'class' => $record['class'],
			'url' => $record['url'],
            'allDay' => false
        );
    }

	echo json_encode($event_array);
?>