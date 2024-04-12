<?php

class VoteController extends BaseController{

    public $user_data;

    public function __construct($data) {
        $this->user_data = $data;
    }

    //up vote
    public function up($post_id){

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

            //check if post exist
            $postsModel = new PostsModel();
            $checkPostExist = $postsModel->checkPostId($post_id);

            if ($checkPostExist < 1){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Posts does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }

            //check
            $votesModel = new VotesModel();
            $checkVoteExist = $votesModel->checkVote($post_id, $data_->id, 1);
            if ($checkVoteExist > 0){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "You already upvoted this post"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }


            //insert data
            $arrSubs = $votesModel->postVote($post_id, $data_->id, 1);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }




    //down vote
    public function down($post_id){

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

            //check if post exist
            $postsModel = new PostsModel();
            $checkPostExist = $postsModel->checkPostId($post_id);

            if ($checkPostExist < 1){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Posts does not exist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }

            //check
            $votesModel = new VotesModel();
            $checkVoteExist = $votesModel->checkVote($post_id, $data_->id, 0);
            if ($checkVoteExist > 0){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "You already upvoted this post"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }


            //insert data
            $arrSubs = $votesModel->postVote($post_id, $data_->id, 0);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }

}
