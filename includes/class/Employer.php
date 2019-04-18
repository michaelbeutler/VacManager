<?php
class Employer
{
    var $id;
    var $name;
    var $shortname;
    var $create_date;
    var $update_date;

    public function __construct($id, $name, $shortname, $create_date, $update_date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->shortname = $shortname;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
    }

    static function construct_mysql($result)
    {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $instance = new self(
                $row['id'],
                $row['name'],
                $row['shortname'],
                $row['create_date'],
                $row['update_date']
            );
            return $instance;
        }
    }

    static function construct_id(Database $database, $id)
    {
        $employer = self::construct_mysql($database->select('SELECT * FROM `employer` WHERE `id`=' . $id . ';'));
        return $employer;
    }

    static function getAll(Database $database)
    {
        $employer_array = (array)null;
        $result = $database->select("SELECT * FROM `employer`;");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $employer = self::construct_id($database, $row['id']);
                $employer->start = date_format(date_create($employer->start), 'd.m.Y');
                $employer->end = date_format(date_create($employer->end), 'd.m.Y');
                $employer_array[] = $employer;
            }
        }

        return $employer_array;
    }

    static function getAllEmployee(Database $database, Employer $employer)
    {
        $user_array = (array)null;
        $result = $database->select("SELECT * FROM `user` WHERE `employer_id`=". $employer->id .";");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = User::construct_id($database, $row['id']);
                $user_array[] = $user;
            }
        }

        return $user_array;
    }

    function getAllVacations($database) {
        if (EmployerPriv::check_employer_priv($database, $this, new Priv(Priv::CAN_ACCEPT))) {
            $vacation_array = (array)null;
            $result = $database->select("SELECT `vacation`.`id` FROM `vacation` LEFT JOIN `user` ON `vacation`.`user_id`=`user`.`id` WHERE `status`='Pending' AND `employer_id`=" . $this->id . ";");
    
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $vacation = Vacation::construct_id($database, $row['id']);
                    $vacation->start = date_format(date_create($vacation->start), 'd.m.Y');
                    $vacation->end = date_format(date_create($vacation->end), 'd.m.Y');
                    $vacation->employer = null;
                    $vacation->user->password = null;
                    $vacation->user->salt = null;
                    $vacation_array[] = $vacation;
                }
            }
    
            return $vacation_array;
        } else {
            return false;
        }  
    } 

    function to_json()
    {
        return json_encode($this);
    }
}
