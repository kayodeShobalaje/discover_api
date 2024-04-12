<?php

class UserValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->identity) || empty($obj->identity)){
            return 'One of more fields are missing';

        } else if (!isset($obj->password) || empty($obj->password)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}