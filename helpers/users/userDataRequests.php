<?php
    function getOneUserInfo($token, $id){
        global $Link;
        if (checkIfAdmin($token) || checkIfIdOwner($token, $id)){
            $userId= $Link->query("SELECT userId, username, roleId, name, surname from users where userId='$id'")->fetch_assoc();
            if (!is_null($userId)){
                echo json_encode($userId);
            }
            else {
                setHTTPStatus("400", "No user with this ID");
            }
        }
        else{
            setHTTPStatus("403", "You do not have permission to view information about this user");
        }
    }
    function deleteUser($token, $id){
        global $Link;
        if (checkIfAdmin($token)){
            $deleteResult = $Link->query("DELETE FROM users WHERE userId='$id'");
            
            if ($deleteResult){
                echo "OK";
            }
            else {
                echo json_encode($Link->error) . PHP_EOL;
                setHTTPStatus("500", "Unexpected Error :(");
            }
        }
        else{
            setHTTPStatus("403", "You do not have permission to delete this user");
        }
    }
    function updateUserInfo($token, $id, $requestData){
        global $Link;
        if (checkIfIdOwner($token, $id)){
            $user = $Link->query("SELECT name, surname from users where userId='$id'")->fetch_assoc();
            
            $name = is_null($requestData->body->name) ? $user['name'] : $requestData->body->name;
            $surname=is_null($requestData->body->surname) ? $user['surname'] : $requestData->body->surname;
            if (is_null($requestData->body->password)){
                $patchRequest =  $Link->query("UPDATE users SET name='$name',surname='$surname' WHERE userId='$id'");
            }
            else{
                $password = hash("sha1", $requestData->body->password); 
                $patchRequest =  $Link->query("UPDATE users SET name='$name',password='$password',surname='$surname' WHERE userId='$id'");
            }

            if ($patchRequest){
                $user = $Link->query("SELECT userId, username, roleId, name, surname  from users where userId='$id'")->fetch_assoc();
                echo json_encode($user);
            }
            else {
                echo json_encode($Link->error) . PHP_EOL;
                setHTTPStatus("500", "Unexpected Error");
            }
        }
        else {
            setHTTPStatus("403", "You can only update your account details");
        }
    }
    function setUserRole($token, $id, $requestData){
        global $Link;
        if (checkIfAdmin($token)){
            $roleId=$requestData->body->roleId;
            if (is_int($roleId)){
                $request =  $Link->query("UPDATE users SET roleId='$roleId' WHERE userId='$id'");
                if ($request){
                    echo "OK";
                }
                else {
                    echo json_encode($Link->error) . PHP_EOL;
                    setHTTPStatus("500", "Unexpected Error here");
                }
            }
            else {
                setHTTPStatus("400", "Incorrect input value");
            }
        }
        else {
            setHTTPStatus("403", "You can only update your account details");
        }
    }
    function getAllUsersInfo($token){
        global $Link;
        if (checkIfAdmin($token)){
            $users= $Link->query("SELECT userId, username, roleId from users");
            if (!is_null($users)){
                $usersArr = [];
                foreach ($users as $userId){
                    $usersArr[] = $userId;
                }    
                echo json_encode($usersArr);
            }
            else {
                setHTTPStatus("500", "Something went wrong");
            }
        }
        
        else {
            setHTTPStatus("403", "You are not an administrator");
        }
    }
?>