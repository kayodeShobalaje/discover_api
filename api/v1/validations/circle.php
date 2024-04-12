<?php

class CircleValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else if (!isset($obj->cover_image) || empty($obj->cover_image)){
            return 'One of more fields are missing';

        } else if (!isset($obj->category) || empty($obj->category)){
            return 'One of more fields are missing';

        } else if (!isset($obj->visibility) || empty($obj->visibility)){
            return 'One of more fields are missing';

        } else if (!isset($obj->allow_interactions) || empty($obj->allow_interactions)){
            return 'One of more fields are missing';

        } else if (!isset($obj->description) || empty($obj->description)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}