<?php
    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';

        global $Link;

        if ($method == 'POST'){
            if (!checkToken()){
                $username = $requestData->body->username;
                $password = $requestData->body->password;

                if (!is_string($username) || !is_string($requestData->body->password) ){
                    setHTTPStatus("400", "Password and username must be a string");
                    return;
                }
                $password = hash("sha1", $requestData->body->password);

                $userId = $Link->query("SELECT userId from users where username='$username' and password='$password'")->fetch_assoc();
                if (!is_null($userId)){
                    $token = bin2hex(random_bytes(16));
                    $userID = $userId['userId'];
                    
                    $time_h = strtotime('now + 10 hours');
                    $timeStamp = date('Y-m-d H:i:s',$time_h);

                    $tokenInsertResult = $Link->query("INSERT INTO tokens(value, userId, validUntil) values ('$token', '$userID', '$timeStamp')");
                    if (!$tokenInsertResult){
                        switch( $Link->errno){
                            case 1292:
                                echo $Link->error;
                                setHTTPStatus("400", "Incorrect type of input values");
                                break;

                            default:
                                setHTTPStatus("500", "Unexpected Error");
                                break;

                        }
                    }
                    else 
                        echo json_encode(['token' => $token]);
                    
                }
                else
                    setHTTPStatus("500", "Something went wrong. Probably, you are not registered");
                
            }
            else 
                setHTTPStatus("403", "Available only for unauthorized users");
        }

        else 
            setHTTPStatus("405", "You can only use POST to /$urlList[0]");
        
    }
?>