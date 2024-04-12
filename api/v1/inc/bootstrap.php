<?php

define("PROJECT_ROOT_PATH", __DIR__ . "/../");

// include main configuration file
require_once PROJECT_ROOT_PATH . "/inc/config.php";

// include the base controller file
require_once PROJECT_ROOT_PATH . "/controller/api/BaseController.php";

// include the use model file
require_once PROJECT_ROOT_PATH . "/model/UserModel.php";
require_once PROJECT_ROOT_PATH . "/model/CircleModel.php";
require_once PROJECT_ROOT_PATH . "/model/CategoryModel.php";
require_once PROJECT_ROOT_PATH . "/model/PostsModel.php";
require_once PROJECT_ROOT_PATH . "/model/CommentsModel.php";
require_once PROJECT_ROOT_PATH . "/model/VotesModel.php";


//validations
require_once PROJECT_ROOT_PATH . "/validations/user.php";
require_once PROJECT_ROOT_PATH . "/validations/circle.php";
require_once PROJECT_ROOT_PATH . "/validations/category.php";
require_once PROJECT_ROOT_PATH . "/validations/posts.php";
require_once PROJECT_ROOT_PATH . "/validations/comments.php";
