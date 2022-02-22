<?php

    function route($method, $urlList, $requestData){
        
        global $Link;

        switch($method){
            case 'GET':
                $token = substr(getallheaders()['Authorization'], 7);
                $user = $Link->query("SELECT userID from tokens where value='$token'")->fetch_assoc();
                    if (!is_null($user)){
                        $userID = $user['userID'];
                        $userFromToken = $Link->query("SELECT * from users where id='$userID'")->fetch_assoc();
                            echo json_encode($userFromToken);
                    }
                    else{
                        echo "400: input data incorrect";
                    }
                break; 
            case 'POST':
                $login = $requestData->body->login;
                $user = $Link->query("SELECT id from users where login='$login'")->fetch_assoc();
                if (is_null($user)) {
                    $password = hash("sha1", $requestData->body->password);
                    $name = $requestData->body->name;
                    $login = $requestData->body->login;
                    $userInsertResult = $Link->query("INSERT INTO users(name, login, password) values ('$name', '$login', '$password')");
                    
                    if (!$userInsertResult){
                        echo "too bad";
                    }
                    else {
                        echo "success";
                    }
                    echo json_encode($requestData);
                }
                else{
                    echo "EXIST";
                }
                break; 
            default:
                break; 
        }

    }

?>