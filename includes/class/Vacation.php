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
    var $vacation_type;
    var $create_date;
    var $update_date;

    function __construct($id, $title, $description, $start, $end, $days, User $user, $accepted, $user_accepted, $vacation_type, $create_date, $update_date)
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
        $this->vacation_type = $vacation_type;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
    }

    static function construct_mysql($result)
    {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user_id_accepted = null;
            if ($row['user_id_accepted'] != null) {
                $user_id_accepted = User::construct_id(new Database(), $row['user_id_accepted']);
            } else {
                $user_id_accepted = array("id"=>null,"username"=>"");
            }
            $instance = new self(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['start'],
                $row['end'],
                $row['days'],
                User::construct_id(new Database(), $row['user_id']),
                $row['accepted'],
                $user_id_accepted,
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

    static function create($database, $title, $description, $start, $end, $days, User $user, $vacation_type_id) {
        return $database->insert("
            INSERT INTO `vacation` (`title`, `description`, `start`, `end`, `days`, `user_id`, `vacation_type_id`)
            VALUES ('$title', '$description', '$start', '$end', $days, $user->id, $vacation_type_id);
        ");
    }

    static function getAll(Database $database, User $user) {
        $vacation_array = (array)null;
        $result = $database->select("SELECT * FROM `vacation` WHERE `user_id`=" . $user->id . ";");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $vacation_array[] = self::construct_id($database, $row['id']);
            }
        }

        return $vacation_array;
    }

    function accept(Database $database, User $user)
    {
        $this->accepted = 1;
        $this->user_accepted = $user;
        return $database->update("UPDATE `vacation` SET `accepted`=1, `user_id_accepted`=$user->id WHERE `id`=$this->id;");
    }

    function refuse(Database $database, User $user)
    {
        //$this->refused = 1;
        //$this->user_refused = $user;
        //return $database->update("UPDATE `vacation` SET `refused`=1, `user_id_refused`=$user->id WHERE `id`=$this->id;");
        return $database->delete("DELETE FROM `vacation` WHERE `id`=$this->id;");
    }

    function cancel(Database $database, User $user)
    {
        if ($this->user != $user) {
            return false;
        }
        //$this->canceled = 1;
        //$this->user_canceled = $user;
        //return $database->update("UPDATE `vacation` SET `canceled`=1, `user_id_canceled`=$user->id WHERE `id`=$this->id;");
        return $database->delete("DELETE FROM `vacation` WHERE `id`=$this->id;");
    }

    function to_json()
    {
        return json_encode($this);
    }
}
