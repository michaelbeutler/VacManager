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
        $database->open();
        $user = self::construct_mysql($database->select('SELECT * FROM `user` WHERE `id`=' . $id . ';'));
        $database->close();
        return $user;
    }

    static function login(Database $database, $username, $password) {
        if (!isset($database, $username, $password)) {
            return false;
        }

        $database->open();
        $result = $database->select("SELECT * FROM `user` WHERE `username`='" . $username . "' LIMIT 1;");
        if ($result->num_rows == 1) {
            if ($row = $result->fetch_assoc()) {
                //if ($row['password'] ==  hash('sha512', $password . $row['salt'])) {
                if ($row['password'] == $password) {
                    return User::construct_id($database, $row['id']);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function to_json()
    {
        return json_encode($this);
    }
}
