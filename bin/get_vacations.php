<?php
require('check_login.php');
if (!check_login()) {
    die();
}
$events = array();


include_once('dbconnect.php');
$conn = openConnection();
//echo $_SESSION['user_id'];
$sql = "SELECT * FROM `tbl_vacation` WHERE `tbl_user_id`=". $_SESSION['user_id'] . " ORDER BY `start` DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $event = (object)array();
        $event->id = $row['id'];
        $event->days = $row['num'];
        $event->title = $row['description'] . ' - ' . $row['num'] . ' Day(s)';
        $event->start = $row['start'];
        if ($row['num'] < 1.0) {
            $event->allDay = false;
        } else {
            $event->allDay = true;
            $row['end'] = date('Y-m-d H:m:s', strtotime($row['end']. ' + 1 days'));
        }

        if (substr($row['end'], 11,20) == "00:00:00") {
            $event->end = substr_replace($row['end'],"23:59:59",11,20);
        } else {
            $event->end = $row['end'];
        }
        

        if ($row['tbl_vacation_type_id'] == 1) {
            $event->color = 'blue';
        } else {
            $event->color = 'yellow';
            $event->textColor = 'black';
        }
        $event->description = $event->title;
        $event->url = 'vacation.php?id='. $row['id'];

        $events[] = $event;
    }
}

echo json_encode($events);
$conn->close();
?>