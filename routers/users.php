<?php
    include_once './helpers/headers.php';
    include_once './helpers/checks.php';

    
    function route($method, $urlList, $requestData){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);

        if ($urlList[1]){
            if (empty( $token )){
                setHTTPStatus("403", "You must be logged in");
            }
            else {
                $id = intval($urlList[1]);
                if (is_numeric($id)){
                    switch($method){
                        case 'GET':
                        
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
                            
                            
                            break;

                        case 'DELETE':
                            if (!empty( $token )){
                                if (checkIfAdmin($token)){
                                
                                    $deleteResult = $Link->query("DELETE FROM users WHERE userId='$id'");
                                    
                                    if ($deleteResult){
                                        echo "success delete";
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
                            else {
                                setHTTPStatus("403", "You must be logged in");
                            }
                            break;

                        case 'PATCH':
                            if (checkIfIdOwner($token, $id)){
                                $password = $requestData->body->password;
                                $name = $requestData->body->name;
                                $surname=$requestData->body->surname;

                                $patchRequest =  $Link->query("UPDATE users SET name='$name',password='$password',surname='$surname' WHERE userId='$id'");
                                if ($patchRequest){
                                    echo "success update";
                                }
                                else {
                                    echo json_encode($Link->error) . PHP_EOL;
                                    setHTTPStatus("500", "Unexpected Error here");
                                }
                            }
                            else {
                                setHTTPStatus("403", "You can only update your account details");
                            }
                            break;
                        default:
                            setHTTPStatus("400", "Not allowed method for /$urlList[0]/$urlList[1]");
                            break; 
                    }
                }
                else {
                    setHTTPStatus("400", "User ID must be a number");
                }
            }
        }

        else {
            if ($method == 'GET'){
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
            else {
                setHTTPStatus("405", "You can only use GET to /$urlList[0]");
            }
        }
    }
          

    

?>