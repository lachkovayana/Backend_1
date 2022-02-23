<?php
    function route($method, $urlList, $requestData){
    include_once './helpers/headers.php';
    include_once 'user/user_helper.php';
    global $Link;
    
    if ($method == 'POST'){
            $username = $requestData->body->username;
            $password = hash("sha1", $requestData->body->password);
            $user = $Link->query("SELECT userId from users where username='$username' and password='$password'")->fetch_assoc();
            if (!is_null($user)){
                $token = bin2hex(random_bytes(16));
                $userID = $user['userId'];
                $tokenInsertResult = $Link->query("INSERT INTO tokens(value, userId) values ('$token', '$userID')");
                if (!$tokenInsertResult){
                    echo json_encode($Link->error);
                }
                else {
                    echo json_encode(['token' => $token]);
                }
            }
            else{
                echo "400: input data incorrect";
            }
        }
        else {
            setHTTPStatus("400", "You can only use POST to '/username'");
        }
   }
?>