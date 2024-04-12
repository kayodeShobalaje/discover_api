<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

//Headers
require "headers.php";
require "token.php";

require __DIR__ . "/inc/bootstrap.php";

//Fetch Path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$user_data='';

//check if it is login route
if ($uri[5] !== 'user' && $uri[6] !== 'login'){

    require "auth.php";
    checkHeaders();

    //get user id
    $user_data = getUserId();

}


//Controllers
require PROJECT_ROOT_PATH . "/controller/api/UserController.php";
require PROJECT_ROOT_PATH . "/controller/api/CircleController.php";
require PROJECT_ROOT_PATH . "/controller/api/CategoryController.php";
require PROJECT_ROOT_PATH . "/controller/api/PostsController.php";
require PROJECT_ROOT_PATH . "/controller/api/CommentsController.php";
require PROJECT_ROOT_PATH . "/controller/api/VoteController.php";
require PROJECT_ROOT_PATH . "/controller/api/PeopleController.php";

$resources = [
    "user",
    "circle",
    "posts",
    "comment",
    "follow",
    "category",
    "comments",
    "votes",
    "people"
];





//check if path exist
if ((isset($uri[2]) && (!isset($uri[5]) || !in_array($uri[5], $resources)) ) || !isset($uri[3])) {

    header("HTTP/1.1 404 Not Found");
    $response = [
        "status" => "error",
        "message" => "Resource not found, refer to API doc"
    ];
    echo json_encode($response);
    exit();

}



//ROUTING
if ($uri[5] == 'user'){

    $actions = [
        "create",
        "list",
        "view",
        "login",
        "suggest",
        "follow"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view') || ($uri[6] == 'follow')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new UserController($user_data);
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }

    //user route
    $objFeedController = new UserController($user_data);
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();


} else if ($uri[5] == 'circle'){

    $actions = [
        "create",
        "list",
        "follow",
        "unfollow",
        "members",
        "posts",
        "explore"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions))  || !isset($uri[6]) || ($uri[6] == 'view') || ($uri[6] == 'members') || ($uri[6] == 'posts') || ($uri[6] == 'follow') || ($uri[6] == 'unfollow')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc>"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new CircleController($user_data);
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || !isset($uri[6]) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc>".$uri[6]

        ];
        echo json_encode($response);
        exit();
    }

    //user route
    $objFeedController = new CircleController($user_data);
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();

} else if ($uri[5] == 'category'){

    $actions = [
        "create",
        "list",
        "add_user_interest",
        "view_user_interest",
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions))){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new CategoryController($user_data);
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || !isset($uri[6]) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }

    //user route
    $objFeedController = new CategoryController($user_data);
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();

} else if ($uri[5] == 'people'){

    $actions = [
        "follow",
        "suggest",
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'follow')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new PeopleController($user_data);
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || !isset($uri[6]) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }

    //user route
    $objFeedController = new PeopleController($user_data);
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();

} else if ($uri[5] == 'posts'){

    $actions = [
        "create",
        "list",
        "view",
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions))  || !isset($uri[6]) || ($uri[6] == 'view')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new PostsController($user_data);
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || !isset($uri[6]) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }

    //user route
    $objFeedController = new PostsController($user_data);
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();

} else if ($uri[5] == 'comments'){

    $actions = [
        "add",
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions))){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new CommentsController($user_data);
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || !isset($uri[6]) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }

    //user route
    $objFeedController = new CommentsController($user_data);
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();

} else if ($uri[5] == 'votes'){

    $actions = [
        "up",
        "down",
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || !isset($uri[6]) || (!isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }

    //user route
    $objFeedController = new VoteController($user_data);
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}($uri[7]);

}