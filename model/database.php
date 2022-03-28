<?php
define('DBNAME','api-controller');
define('DBUSER','root');
define('DBPASSWORD','');
define('DBHOST','localhost');
class Database
{

    public function getConnection(){
        
        $conn = mysqli_connect('localhost','root', '','api-controller');
        if ($conn->connect_error) {
            die('Error failed to connect to mysql'.$conn->connect_error);
        }else{
            return $conn;
        }

    }

}

