<?php
    include_once './helpers/headers.php';
    include_once './helpers/checks.php';

    
    function route($method, $urlList, $requestData){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);

        if (empty( $token )){
            setHTTPStatus("403", "You must be logged in");
        }
        else {
            if ($urlList[1]){
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
                    else{
                        setHTTPStatus("401", "You must be logged in");
                    }
            
            }
        }
    }

    

?>