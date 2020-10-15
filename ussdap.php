<?php
ini_set('error_log', 'ussd-app-error.log');
require 'libs/MoUssdReceiver.php';
require 'libs/MtUssdSender.php';
require 'class/operationsClass.php';
require 'log.php';
require 'db.php';
$production = false;
if ($production == false) {
    $ussdserverurl = 'http://localhost:7000/ussd/send';
} else {
    $ussdserverurl = 'https://api.dialog.lk/ussd/send';
}
$receiver = new UssdReceiver();
$sender = new UssdSender($ussdserverurl, 'APP_000001', 'password');
$operations = new Operations();
$receiverSessionId = $receiver->getSessionId();
$message = $receiver->getMessage(); // get the message content
$phone_number = $receiver->getAddress(); // get the sender's address
$requestId = $receiver->getRequestID(); // get the request ID
$applicationId = $receiver->getApplicationId(); // get application ID
$encoding = $receiver->getEncoding(); // get the encoding value
$version = $receiver->getVersion(); // get the version
$user_id = $receiver->getSessionId(); // get the session ID;
$ussdOperation = $receiver->getUssdOperation(); // get the ussd operation
$gameid = mt_rand(1000, 9999); // get the ussd operation
$responseMsg = array(
    "main" =>
    "**Dice 6**
1. Existing user
2. New user
99. Exit"
);
if ($ussdOperation == "mo-init") {
    try {
        $sessionArrary = array("user_id" => $user_id, "phone_number" => $phone_number, "menu" => "main", 'game_id' => $user_id);

        $operations->setSessions($sessionArrary);
        $sender->ussd($user_id, $responseMsg["main"], $phone_number);
    } catch (Exception $e) {
        $sender->ussd($user_id, 'Sorry error occured try again', $phone_number);
    }
} else {
    $flag = 0;
    $sessiondetails = $operations->getSession($user_id);
    $cuch_menu = $sessiondetails['menu'];
    $operations->session_id = $sessiondetails['user_id'];

    if($cuch_menu == "main" && $receiver->getMessage() == "1"){
        $operations->saveSesssion();
        $sender->ussd($user_id, 'Enter Your ID', $phone_number);
        $getData = $operations->getSession($receiver->getMessage());

        if($getData['game_id'] != "" || $getData['game_id'] != null){
            $operations->session_menu = "exist";
                $responseMsg = array(
                    "game" =>
                    "**Hello '".$getData['game_id']."' **
                1. Face 2 Face
                2. 6 man board
                3. Back
                99. Exit"
                );   
            }
            else{
                $operations->session_menu = "back";
$responseMsg = array(
"game" =>
"**Error ID**
3. Back
99. Exit"
);

                

            }

        $sender->ussd($user_id, $responseMsg["game"], $phone_number);

        // if($operations->session_menu == "exist"){
            
        //     $operations->session_menu = "login";

        //     $sender->ussd($user_id, $getData, $phone_number);

            
        //     // if($getData['game_id'] != "" || $getData['game_id'] != null){
        //     //     $responseMsg = array(
        //     //         "game" =>
        //     //         "**Hello '".$getData['game_id']."' **
        //     //     1. Face 2 Face
        //     //     2. 6 man board
        //     //     3. Back
        //     //     99. Exit"
        //     //     );

        //     //     $sender->ussd($user_id, $responseMsg["game"], $phone_number);
        //     // }
        //     // else{
        //     //     $responseMsg = array(
        //     //         "game" =>
        //     //         "**Invalid ID! **
        //     //     3. Back
        //     //     99. Exit"
        //     //     );

        //     //     $sender->ussd($user_id, $responseMsg["game"], $phone_number);
        //     // }
            

                
        // }
    }
    elseif($cuch_menu == "main" && $receiver->getMessage() == "2"){
        $operations->session_menu = "new";
        $operations->saveSesssion();
        $info =$sender->ussd($user_id, 'Enter Your Nickname', $phone_number);
    }
    elseif($cuch_menu == "main" && $receiver->getMessage() == "99"){
        $operations->session_menu = "Exit";
        $operations->endSesssion();
    }

    // switch ($cuch_menu) {
    //     case "main":  // Following is the main menu
    //         switch ($receiver->getMessage()) {
    //             case "1":
    //                 $operations->session_menu = "exist";
    //                 $operations->saveSesssion();
    //                 $sender->ussd($user_id, 'Enter Your ID', $phone_number);
    //                 break;
    //             case "2":
    //                 $operations->session_menu = "new";
    //                 $operations->saveSesssion();
    //                 $sender->ussd($user_id, 'Enter Your Nickname', $phone_number);
    //                 break;
    //             case "99":
    //                 $operations->session_menu = "Exit";
    //                 $operations->endSesssion();
    //                 break;
    //             default:
    //                 $operations->session_menu = "main";
    //                 $operations->saveSesssion();
    //                 $sender->ussd($user_id, $responseMsg["main"], $phone_number);
    //                 break;
    //         }
    //     break;

    //     case "exist":
    //         switch ($receiver->getMessage() != null){
    //             case $receiver->getMessage():
    //                 $operations->session_menu = "game";
    //                 $operations->session_others = $receiver->getMessage();
    //                 $operations->saveSesssion();
    //                 $responseMsg = array(
    //                 "game" =>
    //                 "**Board Men**
    //             1. 2 man board (face to face)
    //             2. 6 man board (chop n go)
    //             3. Back
    //             99. Exit"
    //             );
    //         $sender->ussd($user_id, $responseMsg["game"], $phone_number);
    //             break;
    //         }
            
    //         break;

    //     case "new":

    //         $responseMsg = array(
    //         "reg" =>
    //         "**Dice 6**
    //     1. Load wallet
    //     2. Back
    //     99. Exit"
    //     );
            
    //         $sender->ussd($user_id, 'You Purchased a medium T-Shirt Your ID ' . $receiver->getMessage(), $phone_number, 'mt-fin');
    //         break;
    //     default:
    //         $operations->session_menu = "main";
    //         $operations->saveSesssion();
    //         $sender->ussd($user_id, 'Incorrect option', $phone_number);
    //         break;
    // }
}