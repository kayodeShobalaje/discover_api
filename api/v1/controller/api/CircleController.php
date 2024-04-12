<?php

class CircleController extends BaseController{

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
        $json = file_get_contents('php://input');
        $data__ = $this->user_data;

        // Converts it into a PHP object
        $data = json_decode($json);

        $userModel = new UserModel();
        $checkExist = $userModel->checkUser($data__->id);

        if ($checkExist < 1){
            //data 
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

            //validations
            $vali = new CircleValidation();
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


            //check if category exist
            $categoryModel = new CategoryModel();
            $checkCategoryExist = $categoryModel->checkCategoryId($data->category);

            if ($checkCategoryExist < 1){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Category does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }


            //check if circle exist
            $circleModel = new CircleModel();
            $checkCircleExist = $circleModel->checkName($data->name);

            if ($checkCircleExist > 0){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Circle already exist, cannot create Duplicate"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }



            // check if other data are valid
            $visibility = ["public", "private"];
            if (!in_array(strtolower($data->visibility), $visibility)){
                $response = [
                    "status" => "error",
                    "message" => "Invalid Visibility type entered"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }



            // check if other data are valid
            $interactions = ["yes", "no"];
            if (!in_array(strtolower($data->allow_interactions), $interactions)){
                $response = [
                    "status" => "error",
                    "message" => "This value is not valid for allowing interactions"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }

            
            //insert data
            $arrSubs = $circleModel->insertCircle($data, $data__->id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

            

        }
        
    }




    //list members of a circle
    public function members($circle_id){

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


        //check user
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

            //check if circle exist
            $circleModel = new CircleModel();
            $checkCircleExist = $circleModel->checkCircleId($circle_id);

            if ($checkCircleExist < 1){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Circle does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }



            //insert data
            $arrSubs = $circleModel->circleMembers($circle_id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }




    //list posts of a circle
    public function posts($circle_id){

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


        //check user
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

            //check if circle exist
            $circleModel = new CircleModel();
            $checkCircleExist = $circleModel->checkCircleId($circle_id);

            if ($checkCircleExist < 1){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Circle does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }



            //insert data
            $arrSubs = $circleModel->circlePosts($circle_id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }




    //up vote
    public function follow($circle_id){

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


        //check user
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

            //check if circle exist
            $circleModel = new CircleModel();
            $checkCircleExist = $circleModel->checkCircleId($circle_id);

            if ($checkCircleExist < 1){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Circle does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }




            //check if circle follow already
            $checkCircleFollowExist = $circleModel->checkCircleFollow($circle_id, $data_->id);

            if ($checkCircleFollowExist > 0){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Already following Circle"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }


            //insert data
            $arrSubs = $circleModel->followCircle($circle_id, $data_->id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }







    //up vote
    public function unfollow($circle_id){

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


        //check user
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

            //check if circle exist
            $circleModel = new CircleModel();
            $checkCircleExist = $circleModel->checkCircleId($circle_id);

            if ($checkCircleExist < 1){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Circle does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }


            //insert data
            $arrSubs = $circleModel->unfollowCircle($circle_id, $data_->id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }





    public function explore(){

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

        $data__ = $this->user_data;
        $user_id = $data__->id;

        $circleModel = new CircleModel();
        $response = $circleModel->explore($user_id);

        if (is_string($response['data'])){
            $response['data']=[];
        }

        //paginated

        $response['message'] = "All circles";
        $response['limit'] = count($response['data']);
        $response['total'] = $circleModel->exploreCount($user_id);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }

}
