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
                $row['id'],
                $row['title'],
                $row['description'],
                $row['start'],
                $row['end'],
                $row['days'],
                User::construct_id(new Database(), $row['user_id']),
                $row['status'],
                $user_id_status,
                VacationType::construct_id(new Database(), $row['vacation_type_id']),
                $row['create_date'],
                $row['update_date']
            );
            return $instance;
        }
    }

    static function construct_id(Database $database, $id)
    {
        $vacation = self::construct_mysql($database->select('SELECT * FROM `vacation` WHERE `id`=' . $id . ';'));
        return $vacation;
    }

    static function create($database, $title, $description, $start, $end, $days, User $user, $vacation_type_id)
    {
        return $database->insert("
            INSERT INTO `vacation` (`title`, `description`, `start`, `end`, `days`, `user_id`, `vacation_type_id`)
            VALUES ('$title', '$description', '$start', '$end', $days, $user->id, $vacation_type_id);
        ");
    }

    function update($database, $title, $description)
    {
        $this->title = $title;
        $this->description = $description;
        return $database->update("
            UPDATE `vacation` SET `title`='$title', `description`='$description' WHERE `id`=$this->id;
        ");
    }


    static function getAll(Database $database, User $user)
    {
        $vacation_array = (array)null;
        $result = $database->select("SELECT * FROM `vacation` WHERE `user_id`=" . $user->id . " ORDER BY `update_date` DESC;");

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
        return $database->update("UPDATE `vacation` SET `status`='Accepted', `user_id_status`=$user->id WHERE `id`=$this->id;");
    }

    function refuse(Database $database, User $user)
    {
        $this->status = 'Refused';
        $this->user_status = $user;
        return $database->update("UPDATE `vacation` SET `status`='Refused', `user_id_status`=$user->id WHERE `id`=$this->id;");
        //return $database->delete("DELETE FROM `vacation` WHERE `id`=$this->id;");
    }

    function cancel(Database $database, User $user)
    {
        if ($this->user != $user) {
            return false;
        }
        $this->status = 'Canceled';
        $this->user_status = $user;
        return $database->update("UPDATE `vacation` SET `status`='Canceled', `user_id_status`=$user->id WHERE `id`=$this->id;");
        //return $database->delete("DELETE FROM `vacation` WHERE `id`=$this->id;");
    }

    function to_json()
    {
        return json_encode($this);
    }
}
