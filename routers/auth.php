<?php
    function route($method, $urlList, $requestData){
    include_once './helpers/headers.php';
    include_once 'user/user_helper.php';

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
                case 'register':
                    $isValidated = true;
                    $validationErrors = [];
    
                    $password = hash("sha1", $requestData->body->password);
                    $name = $requestData->body->name;
                    $username = $requestData->body->username;
                    $surname = $requestData->body->surname;
                    $role = $requestData->body->roleId;
    
                    if (!validatePassword($requestData->body->password)){
                        $isValidated = false;
                        $validationErrors[] = ["Password", "Password is less than 8 characters"];
                    }
                    if (!validateLogin($requestData->body->username)){
                        $isValidated = false;
                        $validationErrors[] = ["Username", "Username is less than 3 characters"];
                    }
                    if (!$isValidated) {
                        $validationMessage = "";
                        foreach ($validationErrors as $err){
                            $validationMessage .= "$err[0]: $err[1] \r\n ";
                        }
                        setHTTPStatus("403", $validationMessage);
                        return;
                    }
    
                    $userInsertResult = $Link->query("INSERT INTO users(name, username, password, surname, roleId) values ('$name', '$username', '$password', '$surname', '$role')");
                    if (!$userInsertResult){
                        echo $Link->errno . " : " . $Link->error . PHP_EOL;
                        if ($Link->errno == 1062){
                            setHTTPStatus("403", "Username '$username' is taken");
                            return;
                        }
                        if ($Link->errno == 1054){
                            setHTTPStatus("403", "No such columns");
                            return;
                        }
                    }
                    else {
                        echo "success";
                    }
                    echo json_encode($requestData);
                    
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