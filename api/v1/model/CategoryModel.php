<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class CategoryModel extends Database{

    public function insertCategory($data, $user){

        $name = ucwords($data->name);
        $created_by = $user;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO category(name, created_by, created_date) VALUES ('$name', '$created_by','$date')");

    }

    public function insertCategoryList($data, $user){

        $category = $data->category;
        $created_by = $user;
        $date = Date('Y-m-d h:i:s');

        $query = "INSERT IGNORE INTO users_category(user_id, category_id, created_date) VALUES ";
        $split = explode(",", $category);

        for ($i=0;$i<count($split);$i++){
            $spl = $split[$i];
            $query .= "('$user', '$spl','$date')";

            if ($i != count($split)-1){
                $query .= ',';
            }
        }

        $query_ = rtrim($query,',');

        
        return $this->insert($query_);

    }

    public function checkCategory($title){
        return $this->count("SELECT COUNT(1) count FROM category WHERE name='$title'");
    }

    public function checkCategoryId($id){
        return $this->count("SELECT COUNT(1) count FROM category WHERE id='$id'");
    }

    public function checkCategoryList($ids){
        return $this->count("SELECT COUNT(1) count FROM category WHERE id IN ($ids)");
    }


    public function listUserCategories($user_id){
        return $this->list("SELECT a.category_id, b.name, a.created_date FROM users_category a
        LEFT JOIN category b ON a.category_id = b.id
        WHERE a.user_id='$user_id'");
    }


    public function listCategories(){
        return $this->list("SELECT id, name FROM category ORDER BY name ASC");
    }


    // public function singleUser($id){
    //     return $this->list("SELECT * FROM users WHERE id='$id'");
    // }

}
