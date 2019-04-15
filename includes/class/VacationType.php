<?php
class VacationType
{
    var $id;
    var $name;
    var $substract_vacation_days;

    public function __construct($id, $name, $substract_vacation_days)
    {
        $this->id = $id;
        $this->name = $name;
        $this->substract_vacation_days = $substract_vacation_days;
    }

    static function construct_mysql($result)
    {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $instance = new self(
                $row['id'],
                $row['name'],
                $row['substract_vacation_days']
            );
            return $instance;
        }
    }

    static function construct_id(Database $database, $id)
    {
        $vacation = self::construct_mysql($database->select('SELECT * FROM `vacation_type` WHERE `id`=' . $id . ';'));
        return $vacation;
    }

    static function getAll(Database $database)
    {
        $vacation_type_array = (array)null;
        $result = $database->select("SELECT * FROM `vacation_type`;");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $vacation_type = self::construct_id($database, $row['id']);
                $vacation_type_array[] = $vacation_type;
            }
        }

        return $vacation_type_array;
    }
}
