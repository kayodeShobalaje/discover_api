<?php

class PostsController extends BaseController{

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
        $vali = new PostValidation();
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


            //check if post exist
            $postsModel = new PostsModel();
            $checkPostExist = $postsModel->checkTitle($data->title);

            if ($checkPostExist > 0){
                //data exist
                $response = [
                    "status" => "error",
                    "message" => "Posts already exist, cannot post Duplicate"
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
            $comments = ["allow", "reject"];
            if (!in_array(strtolower($data->allow_comments), $comments)){
                $response = [
                    "status" => "error",
                    "message" => "This value is not valid for allowing comments"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }


            //insert data
            $arrSubs = $postsModel->insertPosts($data, $data_->id);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }


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

        $postsModel = new PostsModel();
        $response = $postsModel->listPosts();

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "All posts";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }



    public function view($post_id){

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

            exit();
        }

        //create the data
        $json = file_get_contents('php://input');
        $data_ = $this->user_data;

        // Converts it into a PHP object
        $data = json_decode($json);


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

        $full_response = array(
            "status"=> "success",
            "data"=> array(
                "postDetail"=> "",
                "comments"=> "",
                "comments_count"=> "",
                "votes"=> ""
            )
        );


        //fetch
        $arrSubs = $postsModel->singlePost($post_id);

        //comments
        $commentsModel = new CommentsModel();
        $comments_ = $commentsModel->listComments($post_id);

        if (is_string($comments_['data'])){
            $comments_['data']=[];
        }

        //votes
        $votesModel = new VotesModel();
        $votes_ = $votesModel->singlePostVotes($post_id);

        //set data
        $full_response['data']['postDetail'] = $arrSubs['data'][0];
        $full_response['data']['comments'] = $comments_['data'];
        $full_response['data']['comments_count'] = count($comments_['data']);
        $full_response['data']['votes'] = $votes_['data'][0];

        // $upvote = ($full_response['data']['votes'][''])


        $responseData = json_encode($full_response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 Created')
        );

        
        
    }

}
