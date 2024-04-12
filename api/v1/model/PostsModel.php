<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class PostsModel extends Database{

    public function insertPosts($data, $user){

        $title = $data->title;
        $circle_id = (isset($data->circle_id)?$data->circle_id:"");
        $article = addslashes($data->article);
        $tags = $data->tags;
        $category = $data->category;
        $visibility = $data->visibility;
        $allow_comments = $data->allow_comments;
        $media = $data->media;
        $created_by = $user;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO posts(title, article, category, tags, allow_comments, visibility, user_id, circle_id, media, created_date) VALUES ('$title','$article','$category','$tags','$allow_comments','$visibility','$created_by','$circle_id','$media','$date')");

    }

    public function checkTitle($title){
        return $this->count("SELECT COUNT(1) count FROM posts WHERE title='$title'");
    }

    public function checkPostId($id){
        return $this->count("SELECT COUNT(1) count FROM posts WHERE id='$id'");
    }

    public function listPosts(){
        return $this->list("SELECT a.id, a.title, a.article, a.category, a.tags, (SELECT COUNT(1) FROM comments WHERE post_id=a.id) comments, (SELECT COUNT(1) FROM votes WHERE post_id=a.id AND vote_type=1) up_vote, (SELECT COUNT(1) FROM votes WHERE post_id=a.id AND vote_type=0) down_vote, ROUND((((SELECT up_vote)/((SELECT up_vote)+(SELECT down_vote)))*100),2) vote_percent, a.allow_comments, a.visibility, a.user_id, b.first_name, b.last_name, b.profile_pic, b.username, a.media, a.created_date FROM `posts` a LEFT JOIN users b ON a.user_id=b.id WHERE a.circle_id='' OR a.circle_id IS NULL ORDER BY a.created_date DESC");
    }


    public function singlePost($post_id){
        return $this->list("SELECT * FROM posts WHERE id='$post_id'");
    }

}
