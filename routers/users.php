<?php
    include_once './helpers/headers.php';

    function route($method, $urlList, $requestData){
        $admin = "administrator";
        global $Link;

        if ($urlList[3]){
            if (is_numeric($urlList[3])){
                        switch($method){
                            case 'GET':
                                $q=4;
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
                $token = substr(getallheaders()['Authorization'], 7);
                if (!is_null( $token )){
                    $username = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc();
                    if (!is_null($username)){
                        $userId = $username['userId'];
                        $userFromToken = $Link->query("SELECT roleId from users where userId='$userId'")->fetch_assoc();
                        $roleId = $userFromToken['roleId'];
                        $roleOfUser = $Link->query("SELECT name from roles where roleId='$roleId'")->fetch_assoc()['name'];
                    }
                    else {
                        setHTTPStatus("500", "Something went wrong");
                    }

                    if ($roleOfUser == $admin){
                        $users= $Link->query("SELECT userId, username, roleId from users");
                        if (!is_null($users)){
                            $usersArray = [];
                            foreach ($users as $user){
                                $usersArray[] = $user;
                            }    
                            echo json_encode($usersArray);
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