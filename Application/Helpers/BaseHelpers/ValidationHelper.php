<?php

class ValidationHelper
{
    public function ValidateClassicInputs($inputArray)
    {
        $result = new stdClass();

        $result->resultArray = new ArrayObject();
        $result->status = true;

        foreach ($inputArray as $input)
            if (isset($input) && !empty($input))
                $result->resultArray->append(htmlspecialchars(stripslashes(trim($input))));
            else
            {
                $result->resultArray->append('');
                $result->status = false;
            }

        return $result;
    }

    public function ValidateEmails($emailArray)
    {
        $emailRegExPattern = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

        $result = new stdClass();

        $result->resultArray = new ArrayObject();
        $result->status = true;

        foreach ($emailArray as $email)
            if (isset($email) && preg_match($emailRegExPattern, $email) === 1)
                $result->resultArray->append(htmlspecialchars(stripslashes(trim($email))));
            else
            {
                $result->resultArray->append('');
                $result->status = false;
            }

        return $result;
    }

    public function ValidatePasswords($passwords)
    {
        $passwordRegExPattern = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';

        $result = new stdClass();

        $result->resultArray = new ArrayObject();
        $result->status = true;

        foreach ($passwords as $password)
            if (isset($password) && preg_match($passwordRegExPattern, $password) && strpos($password, ' ') !== true)
                $result->resultArray->append(htmlspecialchars(stripslashes($password)));
            else
            {
                $result->resultArray->append(strpos($password, ' '));
                $result->status = false;
            }

        return $result;
    }
}