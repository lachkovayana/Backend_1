<?php
    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';

        global $Link;

        if ($method == 'POST'){
            $username = $requestData->body->username;
            $password = hash("sha1", $requestData->body->password);
            
            if (!is_string($username) || !is_string($requestData->body->password) ){
                setHTTPStatus("400", "Password or username is not a string");
                return;
            }

            $username = $Link->query("SELECT userId from users where username='$username' and password='$password'")->fetch_assoc();
            if (!is_null($username)){
                $token = bin2hex(random_bytes(16));
                $userID = $username['userId'];
                $tokenInsertResult = $Link->query("INSERT INTO tokens(value, userId) values ('$token', '$userID')");
                if (!$tokenInsertResult){
                    // echo json_encode($Link->error);
                    setHTTPStatus("500", "Unexpected Error");
                }
                else {
                    echo json_encode(['token' => $token]);
                }
            }
            else{
                // echo "400: input data incorrect";
                setHTTPStatus("500", "Something went wrong");
            }
        }
        else {
            setHTTPStatus("400", "You can only use GET to /$urlList[0]");
        }
    }
?>