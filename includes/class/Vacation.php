<?php
class Vacation
{
    var $id;
    var $title;
    var $description;
    var $start;
    var $end;
    var $days;
    var $user;
    var $status = 'Pending';
    var $user_status = null;
    var $vacation_type;
    var $create_date;
    var $update_date;

    function __construct($id, $title, $description, $start, $end, $days, User $user, $status, $user_status, $vacation_type, $create_date, $update_date)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->start = $start;
        $this->end = $end;
        $this->days = $days;
        $this->user = $user;
        $this->status = $status;
        $this->user_status = $user_status;
        $this->vacation_type = $vacation_type;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
    }

    static function construct_mysql($result)
    {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user_id_status = null;
            if ($row['user_id_status'] != null) {
                $user_id_status = User::construct_id(new Database(), $row['user_id_status']);
            } else {
                $user_id_status = array("id" => null, "username" => "");
            }
            $instance = new self(
                trim(htmlspecialchars(utf8_encode($row['id']))),
                trim(htmlspecialchars(utf8_encode($row['title']))),
                trim(htmlspecialchars(utf8_encode($row['description']))),
                trim(htmlspecialchars(utf8_encode($row['start']))),
                trim(htmlspecialchars(utf8_encode($row['end']))),
                trim(htmlspecialchars(utf8_encode($row['days']))),
                User::construct_id(new Database(), $row['user_id']),
                trim(htmlspecialchars(utf8_encode($row['status']))),
                $user_id_status,
                VacationType::construct_id(new Database(), $row['vacation_type_id']),
                trim(htmlspecialchars(utf8_encode($row['create_date']))),
                trim(htmlspecialchars(utf8_encode($row['update_date'])))
            );
            return $instance;
        }
    }

    static function construct_id(Database $database, $id)
    {
        $vacation = self::construct_mysql($database->select('SELECT * FROM `vacation` WHERE `id`=' . $id . ';'));
        return $vacation;
    }

    static function create(Database $database, $title, $description, $start, $end, $days, User $user, $vacation_type_id)
    {
        $title = trim(htmlspecialchars(utf8_encode($title)));
        $description = trim(htmlspecialchars(utf8_encode($description)));
        return $database->insert(
            "vacation",
            array(
                'title' => $title, 'description' => $description, 'start' => $start, 'end' => $end,
                'days' => $days, 'user_id' => $user->id, 'vacation_type_id' => $vacation_type_id
            ),
            array('%s', '%s', '%s', '%s', '%d', '%i', '%i')
        );
    }

    function update($database, $title, $description)
    {
        $this->title = $title;
        $this->description = $description;
        return $database->update(
            'vacation',
            array('title' => $title, 'description' => $description),
            array('%s', '%s'),
            array('ID' => $this->id),
            array('%i')
        );
    }


    static function getAll(Database $database, User $user)
    {
        $vacation_array = (array)null;
        $result = $database->select("SELECT * FROM `vacation` WHERE `user_id`=" . $user->id . " ORDER BY `status` ASC, `update_date` DESC;");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $vacation = self::construct_id($database, $row['id']);
                $vacation->start = date_format(date_create($vacation->start), 'd.m.Y');
                $vacation->end = date_format(date_create($vacation->end), 'd.m.Y');
                $vacation_array[] = $vacation;
            }
        }

        return $vacation_array;
    }

    function accept(Database $database, User $user)
    {
        $this->status = 'Accepted';
        $this->user_status = $user;
        return $database->update(
            'vacation',
            array('status' => 'Accepted', 'user_id_status' => $user->id),
            array('%s', '%i'),
            array('ID' => $this->id),
            array('%i')
        );
    }

    function refuse(Database $database, User $user)
    {
        $this->status = 'Refused';
        $this->user_status = $user;
        return $database->update(
            'vacation',
            array('status' => 'Refused', 'user_id_status' => $user->id),
            array('%s', '%i'),
            array('ID' => $this->id),
            array('%i')
        );
    }

    function cancel(Database $database, User $user)
    {
        if ($this->user != $user) {
            return false;
        }
        $this->status = 'Canceled';
        $this->user_status = $user;
        return $database->update(
            'vacation',
            array('status' => 'Canceled', 'user_id_status' => $user->id),
            array('%s', '%i'),
            array('ID' => $this->id),
            array('%i')
        );
    }

    function to_json()
    {
        return json_encode($this);
    }
}
