<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class CircleModel extends Database{

    public function insertCircle($data, $user){

        $name = $data->name;
        $topic = (isset($data->topic)?$data->topic:"");
        $description = addslashes($data->description);
        $category = $data->category;
        $visibility = $data->visibility;
        $allow_interactions = $data->allow_interactions;
        $cover_image = $data->cover_image;
        $created_by = $user;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO circles(name, cover_image, topic, category, restrictions, interactions, descriptions, user_id, created_date)  VALUES ('$name','$cover_image','$topic','$category','$visibility','$allow_interactions','$description','$created_by','$date')");

    }

    public function followCircle($circle_id, $user){

        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO circle_users(circle_id, user_id, created_date)  VALUES ('$circle_id','$user','$date')");

    }

    public function checkName($name){
        return $this->count("SELECT COUNT(1) count FROM circles WHERE name='$name'");
    }

    public function checkCircleId($id){
        return $this->count("SELECT COUNT(1) count FROM circles WHERE id='$id'");
    }

    public function checkCircleFollow($circle_id, $user){
        return $this->count("SELECT COUNT(1) count FROM circle_users WHERE user_id='$user' AND circle_id='$circle_id'");
    }

    public function unfollowCircle($circle_id, $user_id){
        return $this->query("DELETE FROM circle_users WHERE user_id='$user_id' AND circle_id='$circle_id'");
    }

    public function circleMembers($circle_id){
        return $this->list("SELECT a.user_id, b.username, b.first_name, b.last_name, b.profile_pic FROM circle_users a
        LEFT JOIN users b ON a.user_id = b.id
        WHERE a.circle_id='$circle_id'
        ORDER BY a.created_date DESC");
    }

    public function circlePosts($circle_id){
        return $this->list("SELECT * FROM posts WHERE circle_id='$circle_id' ORDER BY created_date DESC");
    }

    public function explore($user_id){
        return $this->list("SELECT *, '' user_images FROM circles a WHERE a.id NOT IN (SELECT circle_id FROM circle_users WHERE user_id='$user_id') LIMIT 10");
    }

    public function exploreCount($user_id){
        return $this->count("SELECT COUNT(1) count FROM circles a WHERE a.id NOT IN (SELECT circle_id FROM circle_users WHERE user_id='$user_id')");
    }
}

