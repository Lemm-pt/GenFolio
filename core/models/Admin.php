<?php
namespace core\models;

use core\classes\Database;

class Admin {


    public function validar($username, $password) {
        $bd = new Database();
        $res = $bd->select("SELECT * FROM admin WHERE username = :username", [':username' => $username]);
        if($res && password_verify($password, $res[0]->password)) {
            return $res[0];
        }
        return false;
    }

    
}