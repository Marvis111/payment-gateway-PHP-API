<?php
header('Content-type: application/json');
header('Content-Allow-Origin-Access: * ');


include '../../api/index.php';

$APIS = new APIcontroller();

//http://localhost/payment/user/api/?userId=2

if ($_SERVER['REQUEST_METHOD'] =='GET') {
    $userID= $_GET['userId'];

//$APIS->test();
    $userApi = $APIS->fetchTestAPIs($userID);

    $res = json_encode($userApi);

    echo $res;

}