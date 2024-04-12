<?php

class CommentsValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->post_id) || empty($obj->post_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->message) || empty($obj->message)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}