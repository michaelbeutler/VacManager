<?php
abstract class Enum
{
    final public function __construct($value)
    {
        $c = new ReflectionClass($this);
        if (!in_array($value, $c->getConstants())) {
            throw IllegalArgumentException();
        }
        $this->value = $value;
    }

    final public function __toString()
    {
        return $this->value;
    }
}

class Priv extends Enum
{
    const CAN_ACCEPT = 'can_accept';
    const CAN_RENAME = 'can_rename';
    const CAN_PRIV = 'can_priv';
    const GENERAL = 'general';
}

class EmployerPriv
{
    var $id;
    var $user;
    var $employer;
    var $can_accept;
    var $can_rename;
    var $can_priv;
    var $create_date;
    var $update_date;

    public function __construct($id, $user, $employer, $can_accept, $can_rename, $can_priv, $create_date, $update_date)
    {
        $this->id = $id;
        $this->user = $user;
        $this->employer = $employer;
        $this->can_accept = $can_accept;
        $this->can_rename = $can_rename;
        $this->can_priv = $can_priv;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
    }

    static function construct_mysql($result)
    {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $instance = new self(
                trim(htmlspecialchars(utf8_encode($row['id']))),
                User::construct_id(new Database(), $row['user_id']),
                Employer::construct_id(new Database(), $row['employer_id']),
                trim(htmlspecialchars(utf8_encode($row['can_accept']))),
                trim(htmlspecialchars(utf8_encode($row['can_rename']))),
                trim(htmlspecialchars(utf8_encode($row['can_priv']))),
                trim(htmlspecialchars(utf8_encode($row['create_date']))),
                trim(htmlspecialchars(utf8_encode($row['update_date'])))
            );
            return $instance;
        }
    }

    static function construct_user(Database $database, User $user, Employer $employer)
    {
        $employer = self::construct_mysql($database->select('SELECT * FROM `employer_privileges` WHERE `user_id`=' . $user->id . ' AND `employer_id`='. $employer->id .' LIMIT 1;'));
        return $employer;
    }

    static function getAll(Database $database, Employer $employer)
    {
        $employer_priv_array = (array)null;
        $result = $database->select("SELECT * FROM `employer_privileges` WHERE `employer_id`=" . $employer->id . ";");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $employer_priv = self::construct_id($database, $row['id']);
                $employer_priv_array[] = $employer_priv;
            }
        }

        return $employer_priv_array;
    }

    static function check_employer_priv(Database $database, Employer $employer, Priv $priv = Priv::GENERAL)
    {
        $user_priv = self::construct_user($database, User::getCurrentUser($database), $employer);
        switch ($priv) {
            case Priv::CAN_ACCEPT:
                    return $user_priv->can_accept;
                break;
            case Priv::CAN_RENAME:
                    return $user_priv->can_rename;
                break;
            case Priv::CAN_PRIV:
                    return $user_priv->can_priv;
                break;
            case Priv::GENERAL:
                    if ($user_priv->can_accept || $user_priv->can_rename || $user_priv->can_priv) {
                        return true;
                    } else {
                        return false;
                    }
                break;
        }
    }

    function to_json()
    {
        return json_encode($this);
    }
}
