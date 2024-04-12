<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class CommentsModel extends Database{

    public function insertComments($data, $user){

        $post_id = $data->post_id;
        $message = addslashes($data->message);
        $created_by = $user;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO comments(user_id, post_id, message, created_date) VALUES ('$created_by','$post_id','$message','$date')");

    }

    public function checkCommentIds($user, $data){
        $post_id = $data->post_id;
        $message = addslashes($data->message);

        return $this->count("SELECT COUNT(1) count FROM comments WHERE user_id='$user' AND post_id='$post_id' AND message='$message'");
    }

    public function listComments($post_id){
        return $this->list("SELECT a.user_id, b.first_name, b.last_name, b.username, b.profile_pic, a.message, a.created_date FROM comments a
        LEFT JOIN users b ON a.user_id = b.id
        WHERE a.post_id='$post_id'
        ORDER BY a.created_date DESC");
    }
    
    // public function checkTitle($title){
    //     return $this->count("SELECT COUNT(1) count FROM posts WHERE title='$title'");
    // }

    // public function checkCategoryList($ids){
    //     return $this->count("SELECT COUNT(1) count FROM category WHERE id IN ($ids)");
    // }


    // public function listUserCategories($user_id){
    //     return $this->list("SELECT a.category_id, b.name, a.created_date FROM users_category a
    //     LEFT JOIN category b ON a.category_id = b.id
    //     WHERE a.user_id='$user_id'");
    // }


    


    // public function singleUser($id){
    //     return $this->list("SELECT * FROM users WHERE id='$id'");
    // }

}
