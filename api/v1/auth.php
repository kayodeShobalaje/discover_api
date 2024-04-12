<?php

require "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getUserId(){
    /**
     * 
     * CHECK FOR AUTHORIZATION HEADERS
     * 
     */
    
    // Set the secret key
    $secret = "dajkldfajklafjdkldfakl";

    $headers = getallheaders();

    $token = explode(" ", $headers['Authorization']);

    $enc = $token[1];

    try {
        // Decode the JWT and verify the signature
        $decoded = JWT::decode($enc, new Key($secret, 'HS256'));
     
        // Output the decoded claims
        // $decoded = (array) $decoded;

        return $decoded;

    } catch (Exception $e) {
        // Handle JWT validation errors

        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Access denied: " . $e->getMessage()
        ];
        echo json_encode($response);
        exit();
    }
}