<?php

class CategoryController extends BaseController{

    public $user_data;

    public function __construct($data) {
        $this->user_data = $data;
    }

    //create new category
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
        $json = file_get_contents('php://input');
        $data_ = $this->user_data;

        // Converts it into a PHP object
        $data = json_decode($json);


        //validations
        $vali = new CategoryValidation();
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



        $userModel = new UserModel();
        $checkExist = $userModel->checkUser($data_->id);
        if ($checkExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "User does not exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //check if category exist
            $categoryModel = new CategoryModel();
            $checkCategoryExist = $categoryModel->checkCategory($data->name);

            if ($checkCategoryExist > 0){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Category exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }

            //insert data
            $arrSubs = $categoryModel->insertCategory($data, $data_->id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }


    //retrieve category list
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

        $categoryModel = new CategoryModel();
        $response = $categoryModel->listCategories();

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "All categories";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }




    //add user interests
    public function add_user_interest(){

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
        $data_ = $this->user_data;

        // Converts it into a PHP object
        $data = json_decode($json);


        //validations
        $vali = new CategoryValidation();
        $val = $vali->validateInterest($data);

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



        $userModel = new UserModel();
        $checkExist = $userModel->checkUser($data_->id);
        if ($checkExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "User does not exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            $split = count(explode(",", $data->category));

            //check if category exist
            $categoryModel = new CategoryModel();
            $checkCategoryExist = $categoryModel->checkCategoryList($data->category);

            if ($checkCategoryExist != $split){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "One or more category does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }

            //insert data
            $arrSubs = $categoryModel->insertCategoryList($data, $data_->id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }





    //retrieve category list
    public function view_user_interest(){

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

        $data_ = $this->user_data;
        $user_id = $data_->id;

        $categoryModel = new CategoryModel();
        $response = $categoryModel->listUserCategories($user_id);

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "All user interest(s)";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }

}
