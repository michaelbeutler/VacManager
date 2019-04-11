<?php

abstract class Enum
{
    final public function __construct($value)
    {
        $c = new ReflectionClass($this);
        if(!in_array($value, $c->getConstants())) {
            throw IllegalArgumentException();
        }
        $this->value = $value;
    }

    final public function __toString()
    {
        return $this->value;
    }
}

class Priv extends Enum {
    const CAN_ACCEPT = 'can_accept';
    const CAN_RENAME = 'can_rename';
    const CAN_PRIV = 'can_priv';
    const GENERAL = 'general';
}

function check_employer_privileges($employer_id, Priv $priv) {

    // check if user is logged in
    if (isset($_SESSION['user_id'], $_SESSION['user_employer_id'], $priv, $employer_id)) {

        // connect to database
        include_once('dbconnect.php');
        $conn = openConnection();

        // select user in database
        $sql = "SELECT `user`.`id`, `user`.`employer_id`, `employer_privileges`.`employer_id` AS 'EID', `employer_privileges`.`can_accept`, `employer_privileges`.`can_rename`, `employer_privileges`.`can_priv`
                FROM `user` LEFT JOIN `employer_privileges` ON `user`.`id` = `employer_privileges`.`user_id` WHERE `user`.`id`=" . $_SESSION['user_id'] . ";";

        // check if execution was successfull
        if ($result = $conn->query($sql)) {
            // check that ther is only one account with this id
            if ($result->num_rows > 0) {
                // loop throw results
                while($row = $result->fetch_assoc()) {
                    
                    switch($priv) {
                        case Priv::CAN_ACCEPT:
                            if ($row['EID'] == $employer_id && $row['can_accept'] == 1) {
                                return true;
                            }
                        break;
                        case Priv::CAN_RENAME:
                            if ($row['EID'] == $employer_id && $row['can_rename'] == 1) {
                                return true;
                            }
                        break;
                        case Priv::CAN_PRIV:
                            if ($row['EID'] == $employer_id && $row['can_priv'] == 1) {
                                return true;
                            }
                        break;
                        case Priv::GENERAL:
                            if ($row['EID'] == $employer_id && $row['can_priv'] == 1 || $row['can_rename'] == 1 || $row['can_accept'] == 1) {
                                return true;
                            }
                        break;
                    }
                    
                }

                return false;
                // close connection
                $conn->close();
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
