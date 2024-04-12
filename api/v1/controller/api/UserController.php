<?php

class UserController extends BaseController{

    public $user_data;

    public function __construct($data) {
        $this->user_data = $data;
    }

    //create new user
    public function create(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'POST') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );

            exit();
        }

        //create the data
        // $json = file_get_contents('php://input');
        $data = $this->user_data;

        // Converts it into a PHP object
        // $data = json_decode($json);

        $userModel = new UserModel();
        $checkExist = $userModel->checkUser($data->id);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "User already exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //insert data
            $arrSubs = $userModel->insertUser($data);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }



    //create new user from community login
    public function login(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'POST') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );

            exit();
        }

        //create the data
        $json = file_get_contents('php://input');

        // Converts it into a PHP object
        $data = json_decode($json);

        $userModel = new UserModel();
        $checkExist = $userModel->checkUser($data->identity);

        if ($checkExist > 0){

            //get data
            $user_dt = $userModel->viewUser($data->identity);
            $token = new Token();
            $sign_jwt_token = $token->signLogin($user_dt['data'][0]);

            //data already exist
            $response = [
                "status" => "success",
                "message" => "Welcome back to Discover",
                "access_token" => $sign_jwt_token
                // "message" => "Login Success. User already exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //validations
            $vali = new UserValidation();
            $val = $vali->validate($data);

            if ($val !== ''){
                $response = [
                    "status" => "error",
                    "message" => $val
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }

            //call TAO User Auth Service
            $curl = curl_init();

            $login_data = [
                "identity" => $data->identity,
                "password" => $data->password
            ];

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://community.boatafrica.com/api/v1/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($login_data, true),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
              ),
            ));

            $response = curl_exec($curl);

            // convert json => object
            $obj = json_decode($response);

            curl_close($curl);
            

            //if auth service is unreachable
            if ($obj->status !== 200){
                $response = [
                    "status" => "error",
                    "message" => "Unable to authenticate user details"
                ];
    
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
    
                $this->sendOutput(
                    json_encode($response), 
                    array('Content-Type: application/json', $strErrorHeader)
                );
    
                exit();
            }

            //insert data
            $arrSubs = $userModel->insertFullUser($obj->data);
            $arrSubs['message'] = 'Welcome Newly to Discover';
            $arrSubs['access_token'] = $obj->data->access_token;

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }



    //retrieve users list
    public function list(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $userModel = new UserModel();
        $response = $userModel->listUsers();

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "All users";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }




    //retrieve single users
    public function view($user_id){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $userModel = new UserModel();
        $response = $userModel->singleUser($user_id);

        $response['message'] = "View single user";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }
}
