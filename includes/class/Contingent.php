<?php
class Contingent
{
    var $id;
    var $contingent;
    var $year;
    var $user;
    var $create_date;
    var $update_date;
    var $used_days;
    var $left_days;

    public function __construct($id = null, $contingent, $year, User $user, $create_date = null, $update_date = null)
    {
        $this->id = $id;
        $this->contingent = $contingent;
        $this->year = $year;
        $this->user = $user;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
    }

    static function construct_mysql($result)
    {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $instance = new self(
                trim(htmlspecialchars(utf8_encode($row['id']))),
                trim(htmlspecialchars(utf8_encode($row['contingent']))),
                trim(htmlspecialchars(utf8_encode($row['year']))),
                User::construct_id(new Database(), $row['user_id']),
                trim(htmlspecialchars(utf8_encode($row['create_date']))),
                trim(htmlspecialchars(utf8_encode($row['update_date'])))
            );
            return $instance;
        }
    }

    static function construct_id(Database $database, $id)
    {
        $contingent = self::construct_mysql($database->select('SELECT * FROM `contingent` WHERE `id`=' . $id . ';'));
        return $contingent;
    }

    static function construct_from_user_id(Database $database, $user_id)
    {
        $contingent = self::construct_mysql($database->select('SELECT * FROM `contingent` WHERE `year`="' . date("Y") . '" AND `user_id`=' . $user_id . ';'));
        return $contingent;
    }

    static function get_contingent(Database $database, User $user, $year)
    {
        $contingent = self::construct_from_user_id($database, $user->id);

        $result = $database->select("SELECT SUM(`days`) AS 'USED DAYS' FROM `vacation` WHERE `vacation_type_id`=1 AND YEAR(`start`)='" . $year . "' AND `status`='Accepted' AND `user_id`=" . $user->id . ";");
        while ($row = $result->fetch_assoc()) {
            $contingent->used_days = floatval($row['USED DAYS']);
            $contingent->left_days = ($contingent->contingent - $contingent->used_days);
        }

        return $contingent;
    }
}
