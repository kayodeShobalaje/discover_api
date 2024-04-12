<?php

class Database{

    protected $connection = null;

    public function __construct(){

        try {

            $this->connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
            // $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);

            if ( mysqli_connect_errno()) {

                throw new Exception("Could not connect to database.");   

            }

        } catch (Exception $e) {

            throw new Exception($e->getMessage());   

        }			

    }

    public function count($query){
        try{

            $q=mysqli_query($this->connection,$query);
            $f = mysqli_fetch_assoc($q);
            $res = $f['count'];
            // mysqli_close($this->connection);

            return $res;

        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }

        return false;
    }

    public function list($query){
        try{

            $q=mysqli_query($this->connection,$query);
            $n=mysqli_num_rows($q);
            $response=array();

            if ($n>0){
                //contains output
                $response['status']='success';
                $response['data'] = array();
                while($f=mysqli_fetch_assoc($q)){
                    array_push($response['data'],$f);
                }
            }
            else{
                $response['status']='error';
                $response['data']='no data found';
            }

            return $response;

        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }

        return false;
    }

    public function query($query){
        try{

            $q=mysqli_query($this->connection,$query);

            if ($q){
                //contains output
                $response['status']='success';
            }
            else{
                $response['status']='error';
                $response['data']=mysqli_error($this->connection);
            }

            return $response;

        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }

        return false;
    }

    public function insert($query){
        try{

            $q=mysqli_query($this->connection,$query);
            $statusx_='';
            $msg='';

            if ($q){
                $statusx_='success';
                $msg = 'Record created successfully';
                
            }
            else{
                $statusx_='error';
                $msg = 'error: '.mysqli_error($this->connection);
            }

            // mysqli_close($this->connection);
            $res = [
                "status" => $statusx_,
                "message" => $msg
            ];

            return $res;

        } catch(Exception $e) {

            echo $query;
            throw New Exception( $e->getMessage() );
        }

        return false;
    }

    public function select($query = "" , $params = []){

        try {

            $stmt = $this->executeStatement( $query , $params );
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);				
            $stmt->close();

            return $result;

        } catch(Exception $e) {

            throw New Exception( $e->getMessage() );

        }

        return false;

    }

    public function executeStatement($query = "" , $params = []){

        try {

            $stmt = $this->connection->prepare( $query );

            if($stmt === false) {

                throw New Exception("Unable to do prepared statement: " . $query);

            }

            if( $params ) {

                $stmt->bind_param($params[0], $params[1]);

            }

            $stmt->execute();

            return $stmt;

        } catch(Exception $e) {

            throw New Exception( $e->getMessage() );

        }	

    }

}
