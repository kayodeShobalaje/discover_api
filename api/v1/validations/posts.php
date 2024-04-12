<?php

class PostValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->title) || empty($obj->title)){
            return 'One of more fields are missing';

        } else if (!isset($obj->article) || empty($obj->article)){
            return 'One of more fields are missing';

        } else if (!isset($obj->category) || empty($obj->category)){
            return 'One of more fields are missing';

        } else if (!isset($obj->tags) || empty($obj->tags)){
            return 'One of more fields are missing';

        } else if (!isset($obj->allow_comments) || empty($obj->allow_comments)){
            return 'One of more fields are missing';

        } else if (!isset($obj->visibility) || empty($obj->visibility)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }//media is optional
    }

}