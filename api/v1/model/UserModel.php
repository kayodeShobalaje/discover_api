<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class UserModel extends Database{

    public function insertUser($data){

        $id = $data->id;
        $email = $data->email;
        $first_name = $data->first_name;
        $last_name = $data->last_name;
        $phone = "";//$data->phone
        $profile_pic = "";
        $gender = "";
        $account_number = $data->account_number;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO users(id, email, first_name, last_name, phone, profile_pic, gender, account_number, is_deactivated, created_date) VALUES ('$id', '$email','$first_name','$last_name','$phone','$profile_pic','$gender','$account_number','0','$date')");

    }

    public function insertFullUser($data){

        $id = $data->id;
        $email = $data->email;
        $first_name = $data->first_name;
        $last_name = $data->last_name;
        $phone = $data->phone;
        $dob = $data->date_of_birth;
        $username = $data->username;
        $profile_pic = $data->profile_pic;
        $gender = $data->gender;
        $account_number = $data->account_number;
        $is_deactivated = 'false';//$data->is_deactivated
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO users(id, email, first_name, last_name, phone, dob, username, profile_pic, gender, account_number, is_deactivated, created_date) VALUES ('$id', '$email', '$first_name', '$last_name', '$phone', '$dob', '$username', '$profile_pic', '$gender', '$account_number', '$is_deactivated', '$date')");

    }

    public function insertFriends($user_id, $friend_id){

        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO friends(user_id, friend_user_id, is_approved, created_date) VALUES ('$user_id', '$friend_id','y','$date')");

    }

    public function checkUser($email){
        //email or user_id
        return $this->count("SELECT COUNT(1) count FROM users WHERE email='$email' OR id='$email'");
    }


    public function listUsers(){
        return $this->list("SELECT * FROM users");
    }


    public function singleUser($id){
        return $this->list("SELECT * FROM users WHERE id='$id'");
    }


    public function suggestUsers($user_id){
        return $this->list("SELECT * FROM users WHERE id <> '$user_id' LIMIT 10");
    }


    public function checkFriends($user_id, $friend_id){
        return $this->count("SELECT COUNT(1) count FROM friends WHERE (user_id='$user_id' AND friend_user_id='$friend_id') OR (user_id='$friend_id' AND friend_user_id='$user_id')");
    }


    public function viewUser($email){
        return $this->list("SELECT * FROM users WHERE email='$email'");
    }

}
