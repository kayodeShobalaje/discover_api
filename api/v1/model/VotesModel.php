<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class VotesModel extends Database{

    public function postVote($post_id, $user_id, $type){

        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO votes(user_id, post_id, vote_type, created_date) VALUES ('$user_id','$post_id','$type','$date')");

    }

    public function checkVote($post_id, $user_id, $type){
        return $this->count("SELECT COUNT(1) count FROM votes WHERE user_id='$user_id' AND post_id='$post_id' AND vote_type='$type'");
    }

    public function singlePostVotes($post_id){
        return $this->list("SELECT COUNT(CASE WHEN vote_type=1 THEN 1 END) up_vote, COUNT(CASE WHEN vote_type=0 THEN 1 END) down_vote FROM `votes` WHERE post_id='$post_id'");
    }

}
