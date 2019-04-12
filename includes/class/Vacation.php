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
    var $accepted = false;
    var $user_accepted = null;
    var $vacation_type_id = 1;
    var $create_date;
    var $update_date;

    function __construct($id, $title, $description, $start, $end, $days, User $user, $accepted, User $user_accepted, $vacation_type_id, $create_date, $update_date)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->start = $start;
        $this->end = $end;
        $this->days = $days;
        $this->user = $user;
        $this->accepted = $accepted;
        $this->user_accepted = $user_accepted;
        $this->vacation_type_id = $vacation_type_id;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
    }

    static function construct_mysql($result)
    {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $instance = new self(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['start'],
                $row['end'],
                $row['days'],
                User::construct_id(new Database(), $row['user_id']),
                $row['accepted'],
                User::construct_id(new Database(), $row['user_id_accepted']),
                $row['vacation_type_id'],
                $row['create_date'],
                $row['update_date']
            );
            return $instance;
        }
    }

    static function construct_id(Database $database, $id)
    {
        $database->open();
        $vacation = self::construct_mysql($database->select('SELECT * FROM `vacation` WHERE `id`=' . $id . ';'));
        $database->close();
        return $vacation;
    }

    function accept($user)
    {
        $this->accepted = 1;
    }

    function to_json()
    {
        return json_encode($this);
    }
}
