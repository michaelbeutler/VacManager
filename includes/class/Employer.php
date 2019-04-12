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
        $database->open();
        $employer = self::construct_mysql($database->select('SELECT * FROM `employer` WHERE `id`=' . $id . ';'));
        $database->close();
        return $employer;
    }

    function to_json()
    {
        return json_encode($this);
    }
}
