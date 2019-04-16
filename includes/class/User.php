<?php
class User
{
    var $id;
    var $username;
    var $email;
    var $firstname;
    var $lastname;
    var $admin;
    var $is_banned;
    var $password;
    var $salt;
    var $create_date;
    var $update_date;
    var $employer;

    public function __construct($id, $username, $email, $firstname, $lastname, $admin, $is_banned, $password, $salt, $create_date, $update_date, Employer $employer)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->admin = $admin;
        $this->is_banned = $is_banned;
        $this->password = $password;
        $this->salt = $salt;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
        $this->employer = $employer;
    }

    static function construct_mysql($result)
    {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $instance = new self(
                $row['id'],
                $row['username'],
                $row['email'],
                $row['firstname'],
                $row['lastname'],
                $row['admin'],
                $row['is_banned'],
                $row['password'],
                $row['salt'],
                $row['create_date'],
                $row['update_date'],
                Employer::construct_id(new Database(), $row['employer_id'])
            );
            return $instance;
        }
    }

    static function construct_id(Database $database, $id)
    {
        $user = self::construct_mysql($database->select("SELECT * FROM `user` WHERE `id`=" . $id . ";"));
        return $user;
    }

    static function login(Database $database, $username, $password)
    {
        if (!isset($database, $username, $password)) {
            return false;
        }

        Session::start();

        $result = $database->select("SELECT * FROM `user` WHERE `username`='" . $username . "' LIMIT 1;");
        if ($result->num_rows == 1) {
            if ($row = $result->fetch_assoc()) {
                if ($row['password'] ==  hash('sha512', $password . $row['salt'])) {
                    Session::assing(self::construct_id($database, $row['id']));
                    return true;
                }
            }
        }
        return false;
    }

    static function logout()
    {
        return Session::destroy();
    }

    static function check_login($database, $check_if_admin = 0)
    {
        if (isset($_SESSION['user_id'], $_SESSION['user_password'], $_SESSION['user_is_admin'])) {
            $user = User::construct_id($database, $_SESSION['user_id']);
            if ($user->password == $_SESSION['user_password'] && $user->is_banned !== 1) {
                if ($check_if_admin == 1) {
                    if ($user->admin == 0) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

    static function getCurrentUser($database)
    {
        if (self::check_login($database)) {
            return User::construct_id($database, $_SESSION['user_id']);
        }
        return false;
    }

    static function getAll(Database $database)
    {
        $user_array = (array)null;
        $result = $database->select("SELECT * FROM `user`;");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = self::construct_id($database, $row['id']);
                $user->create_date = date_format(date_create($user->create_date), 'd.m.Y');
                $user->update_date = date_format(date_create($user->update_date), 'd.m.Y');
                $user_array[] = $user;
            }
        }

        return $user_array;
    }

    function to_json()
    {
        return json_encode($this);
    }
}
