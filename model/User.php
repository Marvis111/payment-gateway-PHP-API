<?php

include_once 'database.php';

$db = new Database();

$conn = $db->getConnection();

class User
{
   
  
    public function fetchUser($userId){
        $sql = "SELECT * FROM usertestapiauth WHERE userId = '$userId' ";
        $query = mysqli_query($GLOBALS['conn'],$sql);
        if ($query) {
            if (mysqli_num_rows($query) == 1 ) {
                return mysqli_fetch_assoc($query);
            }else {
                return ['no user'];
            }
        }
    }


   public function create($userId,$publicKey,$secKey,$encKey){
        $sql = "INSERT INTO usertestapiauth( userId, PUBLIC_KEY,SECRET_KEY, API_KEY)
                VALUES('$userId','$publicKey','$secKey','$encKey') 
         ";
        $query = mysqli_query($GLOBALS['conn'],$sql);
            return true;
    }

}
