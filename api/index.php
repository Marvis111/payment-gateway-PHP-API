<?php

include '../../model/User.php';
//db
//
$user = new User();
//
//print_r($user->fetchUser(1));

class APIController {
    public $PUBLIC_KEY , $SECRET_KEY;
    protected $API_KEY;

    function __constructor(){
        $this->PUBLIC_KEY = null;
        $this->SECRET_KEY = null;
        $this->API_KEY = null;
    }
    private function createKeys(){
        //FOR STARTER
        //the keys should enter into the db...
    if ($this->PUBLIC_KEY == null &&
       $this->SECRET_KEY == null &&
        $this->API_KEY == null) {
        $this->PUBLIC_KEY = uniqid(400);
        $this->SECRET_KEY = uniqid(400);
        $this->API_KEY = uniqid(400);
        
       }
       return true;
    }
    public function fetchTestAPIs($userId){
        $userAuthTest = $GLOBALS['user']->fetchUser($userId);
      //  print($userAuthTest);
        if (count($userAuthTest) != 0) {
            return $userAuthTest;
        }else{
            if ($this->createKeys()) {
               $useraAuthTest =  $GLOBALS['user']->create($userId,$this->PUBLIC_KEY, $this->SECRET_KEY,$this->API_KEY);

               $newuserAuthTESTAPIs =  $GLOBALS['user']->fetchUser($userId);

                return $newuserAuthTESTAPIs;
            }
           
        } 
    }
    public function test(){
        print_r($GLOBALS['user']);
    }



}