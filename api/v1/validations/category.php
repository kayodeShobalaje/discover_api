<?php

class CategoryValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

    public function validateInterest($obj){
        //receive data

        if (!isset($obj->category) || empty($obj->category)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}