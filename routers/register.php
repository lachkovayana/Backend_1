<?php
    function route($method, $urlList, $requestData){
    include_once './helpers/headers.php';
    include_once './helpers/validation.php';

    global $Link;

        if ($method == 'POST'){
            $isValidated = true;
            $validationErrors = [];
            
            $password = $requestData->body->password;
            $name = $requestData->body->name;
            $username = $requestData->body->username;
            $surname = $requestData->body->surname;
            
            if (is_null($password) || is_null($name) || is_null($username) || is_null($surname) ){
                $validationMessage = "Not all data entered";
                setHTTPStatus("400", $validationMessage);
                return;
            }

            if (!validatePassword($password)){
                $isValidated = false;
                $validationErrors[] = ["Password", "Password is less than 8 characters"];
            }
            if (!validateLogin($username)){
                $isValidated = false;
                $validationErrors[] = ["Username", "Username is less than 3 characters"];
            }
           

            if (!$isValidated ) {
                $validationMessage = "";
                foreach ($validationErrors as $err){
                    $validationMessage .= "$err[0]: $err[1] \r\n ";
                }
                setHTTPStatus("400", $validationMessage);
                return;
            }
            
            $password = hash("sha1", $requestData->body->password);
            
            $userInsertResult = $Link->query("INSERT INTO users(name, username, password, surname) values ('$name', '$username', '$password', '$surname')");
            if ($userInsertResult){
                $userId = $Link->query("SELECT userId from users where username='$username' and password='$password'")->fetch_assoc();
                $token = bin2hex(random_bytes(16));
                $userID = $userId['userId'];
                $tokenInsertResult = $Link->query("INSERT INTO tokens(value, userId) values ('$token', '$userID')");
                if (!$tokenInsertResult){
                    setHTTPStatus("500", "Unexpected Error");
                }
                else {
                    echo json_encode(['token' => $token]);
                }
            }
            else {
                echo $Link->errno . " : " . $Link->error . PHP_EOL;
                if ($Link->errno == 1062){
                    setHTTPStatus("400", "Username '$username' is taken");
                    return;
                }
                if ($Link->errno == 1054){
                    setHTTPStatus("400", "No such columns");
                    return;
                }
                if ($Link->errno == 1366){
                    setHTTPStatus("400", "Incorrect value in some column");
                    return;
                }
                if ($Link->errno == 1452){
                    setHTTPStatus("400", "No such role");
                    return;
                }
                else {
                    setHTTPStatus("500", "Something went wrong");
                }
            }
            
            
        }
        else {
            setHTTPStatus("405", "You can only use POST to /$urlList[0]");
        }
   }
?>