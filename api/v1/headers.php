<?php
function checkHeaders(){
    /**
     * 
     * CHECK FOR AUTHORIZATION HEADERS
     * 
     */

    $headers = getallheaders();

    //check for authorization
    if (!isset($headers['Authorization'])){

        header("HTTP/1.1 400 Bad Request");
        $response = [
            "status" => "error",
            "message" => "Authorization headers not sent."
        ];
        echo json_encode($response);
        exit();

    } else{
        //check if it has bearer

        if (strpos($headers['Authorization'], "Bearer") === false) {
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Authorization must be of type [Bearer Token]"
            ];
            echo json_encode($response);
            exit();

        } else{

            $token = explode(" ", $headers['Authorization']);

            if (count($token) !== 2){
                header("HTTP/1.1 404 Not Found");
                $response = [
                    "status" => "error",
                    "message" => "Invalid Authorization format [Bearer Token]"
                ];
                echo json_encode($response);
                exit();
            }
        }
    }
}