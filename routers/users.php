<?php
    include_once './helpers/headers.php';
    include_once './helpers/checks.php';

    
    function route($method, $urlList, $requestData){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);

        if ($urlList[1]){
            if (is_numeric($urlList[1])){
                switch($method){
                    case 'GET':
                        $id = intval($urlList[1]);
                        if (checkIfAdmin($token) || checkIfIdOwner($token, $id)){
                            $user= $Link->query("SELECT userId, username, roleId, name, surname from users where userId='$id'")->fetch_assoc();
                            if (!is_null($user)){
                                echo json_encode($user);
                            }
                            else {
                                setHTTPStatus("400", "No user with this ID");
                            }
                        }
                        else{
                            setHTTPStatus("403", "You do not have permission to view information about this user");
                        }
                    default:
                    break; 
                }
            }
            else {
                setHTTPStatus("400", "User ID must be a number");
            }

        }

        else {
            if ($method == 'GET'){
                
                if (!is_null( $token )){
                    if (checkIfAdmin($token)){
                        $users= $Link->query("SELECT userId, username, roleId from users");
                        if (!is_null($users)){
                            $users = [];
                            foreach ($users as $user){
                                $users[] = $user;
                            }    
                            echo json_encode($users);
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
                    setHTTPStatus("403", "Authorization token are invalid");
                }
            }
            else {
                setHTTPStatus("400", "You can only use GET to '/users'");
            }
        }
    }
          

    

?>