<?php
    include_once 'user/user_helper.php';
    include_once './helpers/headers.php';

    function route($method, $urlList, $requestData){
        
        global $Link;

        switch($method){
            case 'GET':
                $token = substr(getallheaders()['Authorization'], 7);
                $username = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc();
                if (!is_null($username)){
                    $userId = $username['userId'];
                    $userFromToken = $Link->query("SELECT * from users where userId='$userId'")->fetch_assoc();
                        echo json_encode($userFromToken);
                }
                else{
                    setHTTPStatus("400", "Input data incorrect");
                }
                break; 

            // case 'POST':
            //     $isValidated = true;
            //     $validationErrors = [];

            //     $password = hash("sha1", $requestData->body->password);
            //     $name = $requestData->body->name;
            //     $username = $requestData->body->username;
            //     $surname = $requestData->body->surname;
            //     $role = $requestData->body->roleId;

            //     if (!validatePassword($requestData->body->password)){
            //         $isValidated = false;
            //         $validationErrors[] = ["Password", "Password is less than 8 characters"];
            //     }
            //     if (!validateLogin($requestData->body->username)){
            //         $isValidated = false;
            //         $validationErrors[] = ["Username", "Username is less than 3 characters"];
            //     }
            //     if (!$isValidated) {
            //         $validationMessage = "";
            //         foreach ($validationErrors as $err){
            //             $validationMessage .= "$err[0]: $err[1] \r\n ";
            //         }
            //         setHTTPStatus("403", $validationMessage);
            //         return;
            //     }

            //     $userInsertResult = $Link->query("INSERT INTO users(name, username, password, surname, roleId) values ('$name', '$username', '$password', '$surname', '$role')");
            //     if (!$userInsertResult){
            //         echo $Link->errno . " : " . $Link->error . PHP_EOL;
            //         if ($Link->errno == 1062){
            //             setHTTPStatus("403", "Username '$username' is taken");
            //             return;
            //         }
            //         if ($Link->errno == 1054){
            //             setHTTPStatus("403", "No such columns");
            //             return;
            //         }
            //     }
            //     else {
            //         echo "success";
            //     }
            //     echo json_encode($requestData);
                
            //     break; 
            default:
                break; 
        }

    }

?>