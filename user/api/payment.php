<?php

header('Content-type: application/json');
header('Content-Allow-Origin-Access: * ');


include '../../api/index.php';


$APIS = new APIcontroller();

//error filter


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $email  = $_POST['email'];
    $currency = $_POST['currency'];
    $phoneNo = $_POST['phoneNo'];
    $paymentCond = $_POST['PAY_COND'];//user should be able to switch between test and live..
    $userID = isset( $_SESSION['userId']) ? $_SESSION['userId'] : 1 ;
    $userApiKeys = $APIS->fetchTestAPIs($userID);
    $SECRET_KEY = $userApiKeys['SECRET_KEY'];

    $userAuthDetails = $paymentCond == 'TEST' ?
    $SECRET_KEY
     :
      'WE WILL FETCH THE LIVE API HERE...';
    //aray of errors..
    $errorArray  = array();

    //validation...prototype: ['fieldname'=>"field error message"]
    array_push($errorArray, ['name' => "name","msg"=>(empty($name)?'Name is required':"") ]);
    array_push($errorArray, ['name' => "email","msg"=>(empty($email)?'Email is required':"") ]);
    array_push($errorArray, ['name' => "amount","msg"=>(empty($amount)?'Amount is required':"") ]);
    array_push($errorArray, ['name' => "currency","msg"=>(empty($currency)?'Currency is required':"") ]);
    array_push($errorArray, ['name' => "phoneNo","msg"=>(empty($phoneNo)?'Number is required':"") ]);
    array_push($errorArray, ['name' => "paymentCond","msg"=>(empty($paymentCond)?' Select between lIVE/ TEST':"") ]);

    //redirect url 
  //the redirected url.... success page..

    $redirect_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http')
    . '://'.$_SERVER['HTTP_HOST'].'payment.php';



   //no error...
   function filterError($errField){
    return ($errField['msg'] != "" );
}

    if (count(array_filter($errorArray,'filterError')) == 0) {
        $request = [
            'tx_ref' => time(),
            'amount' => $amount,
            'currency' => $currency,
            'payment_option' => 'card',
            'redirect_url' => $redirect_url,
            //all the post data 
            "customer" => $_POST,
            //we might need this sha if we have db ready for the users...
            "meta" => [],
            "customization" => [
                'description' => $description,
                'time' => 'time is not a problem...'
            ]
            ];
          //initialize client url..
        $curl = curl_init();
    //
        curl_setopt_array($curl, array(
            //flutter wave endpoint
            CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                //PAY_CON = TEST / LIVE
                //default userid = 1... if session is created, use the session userid..
                'Authorization: Bearer '.$userAuthDetails,
                'Content-Type: application/json'
            ),
            ));
          
            $response = curl_exec($curl);
            curl_close($curl);
            //decode the json object to readable php
            $res = json_decode($response);
    
            print_r($res);
    
            
            if ($res->status == 'success') {
                $checkoutPage = $res->data->link;
                //we will embed this in our check out page.. but for now...
                header('location:'.$checkoutPage);
            }else {
                echo 'Error using this gateway';
                //call another gateway endpoint.... //paystack
            }
            
    }else{
        //error in json..
        echo json_encode($errorArray);
    }

}else {
    echo 'hello';
}

/**
 * dummy postman test data
 * {
    "name":"MARVELLOS",
    "currency":"NGN",
    "email":"oyegbilegbemiga@gmail.com",
    "description":"This is a test",
    "amount":1000,
    "phoneNo":"09133950674",
    "PAY_COND":"TEST"
}
 * 
 */