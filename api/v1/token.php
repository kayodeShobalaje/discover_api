<?php

require "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token{

    public function signLogin($payload){

        // Set the secret key
        $secret = "dajkldfajklafjdkldfakl";

        $jwt = JWT::encode($payload, $secret, 'HS256');

        return $jwt;
    }
}