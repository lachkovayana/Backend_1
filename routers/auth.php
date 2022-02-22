<?php
    function route($method, $urlList, $requestData){
        if ($method == 'POST'){
            global $Link;
            switch($urlList[1]){
                case 'login':
                    $login = $requestData->body->login;
                    $password = hash("sha1", $requestData->body->password);
                    $user = $Link->query("SELECT id from users where login='$login' and password='$password'")->fetch_assoc();
                    if (!is_null($user)){
                        $token = bin2hex(random_bytes(16));
                        $userID = $user['id'];
                        $tokenInsertResult = $Link->query("INSERT INTO tokens(value, userID) values ('$token', '$userID')");
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
                    break;
                case 'logout':
                    break;
                default:
                    setHTTPStatus("404", "There is no path as 'auth/$urlList[1]'");
                    break;
            }
        }
        else {
            setHTTPStatus("400", "You can only use POST to 'auth/'");
        }
   }
?>