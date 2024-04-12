<?php

class CommentsController extends BaseController{

    public $user_data;

    public function __construct($data) {
        $this->user_data = $data;
    }

    //create new category
    public function add(){

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
        $vali = new CommentsValidation();
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


            //check if post exist
            $postsModel = new PostsModel();
            $checkPostExist = $postsModel->checkPostId($data->post_id);

            if ($checkPostExist < 1){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Post does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }


            //check if comment exist on post
            $commentsModel = new CommentsModel();
            $checkCommentExist = $commentsModel->checkCommentIds($data_->id, $data);
            if ($checkCommentExist > 0){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Duplicate Comment"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }



            //insert data
            $arrSubs = $commentsModel->insertComments($data, $data_->id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }

}
