<?php
class Validator
{

    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function doPasswordsMatch($password, $repeatPassword)
    {
        return $password === $repeatPassword;
    }

    public static function isStrongPassword($password)
    {
        return strlen($password) >= 6;
    }
}
?>
