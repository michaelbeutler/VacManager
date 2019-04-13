<?php
class Session {
    static function start() {
        return session_start();
    }

    static function destroy() {
        return session_destroy();
    }

    static function assing(User $user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_password'] = $user->password;
        $_SESSION['user_is_admin'] = $user->admin;
        $_SESSION['employer_id'] = $user->employer->id;
        $_SESSION['employer_name'] = $user->employer->name;
        $_SESSION['employer_shortname'] = $user->employer->shortname;
    }
}