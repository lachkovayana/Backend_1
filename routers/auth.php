<?php
    function route($method, $urlList, $requestData){
        if ($method == 'POST'){
            $link = mysqli_connect("127.0.0.1", "backend_demo_1", "password", "backend_demo_1");
            switch($urlList[1]){
                case 'login':
                    $login = $requestData->body->login;
                    $password = hash("sha1", $requestData->body->password);
                    $user = $link->query("SELECT id from users where login='$login' and password='$password'")->fetch_assoc();
                    if (!is_null($user)){
                        $token = bin2hex(random_bytes(16));
                        $userID = $user['id'];
                        $tokenInsertResult = $link->query("INSERT INTO tokens(value, userID) values ('$token', '$userID')");
                        if (!$tokenInsertResult){
                            echo json_encode($link->error);
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
                    break;
            }
        }
        else {
            echo "bad request";
        }
   }
?>