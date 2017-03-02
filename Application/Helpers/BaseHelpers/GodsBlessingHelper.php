<?php

class GodsBlessingHelper
{
    public function HashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}