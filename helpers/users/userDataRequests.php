<?php
    function getOneUserInfo($id){
        global $Link;
        $userId= $Link->query("SELECT userId, username, roleId, name, surname from users where userId='$id'")->fetch_assoc();
        if ($userId){
            echo json_encode($userId);
        }
        else {
            setHTTPStatus("400", "No user with this ID");
        }
       
    }
    function deleteUser($id){
        global $Link;
        $deleteResult = $Link->query("DELETE FROM users WHERE userId='$id'");
        if ($deleteResult){
            echo setHTTPStatus("200", "OK");
        }
        else {
            echo json_encode($Link->error) . PHP_EOL;
            setHTTPStatus("500", "Unexpected Error :(");
        }
        
    }
    function updateUserInfo($id, $requestData){
        global $Link;
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
    function setUserRole( $id, $requestData){
        global $Link;
        $roleId=$requestData->body->roleId;
        if (is_int($roleId)){
            $request =  $Link->query("UPDATE users SET roleId='$roleId' WHERE userId='$id'");
            if ($request){
               setHTTPStatus("200", "OK");
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
    function getAllUsersInfo(){
        global $Link;
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
?>