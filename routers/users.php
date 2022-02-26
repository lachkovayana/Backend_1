<?php
    include_once './helpers/headers.php';
    include_once './helpers/checks.php';
    include_once './helpers/users/userDataRequests.php';

    
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

                    if ($urlList[2] == "role"){
                        if ($method == 'POST'){
                           setUserRole($token, $id, $requestData);
                        }
                        else{
                            setHTTPStatus("405", "You can only use POST to /$urlList[0]//$urlList[1]//$urlList[2]");
                        }
                    }
                    else { 
                        switch($method){
                            case 'GET':
                                getOneUserInfo($token, $id);
                                break;

                            case 'DELETE':
                                deleteUser($token, $id);
                                break;

                            case 'PATCH':
                                updateUserInfo($token, $id, $requestData);
                                break;
                            default:
                                setHTTPStatus("405", "Not allowed method for /$urlList[0]/$urlList[1]");
                                break; 
                        }
                    }
                }
                else {
                    setHTTPStatus("400", "Incorrect value of user ID");
                }
            }
        }

        else {
            if ($method == 'GET'){
               getAllUsersInfo($token);
            }
            else {
                setHTTPStatus("405", "You can only use GET to /$urlList[0]");
            }
        }
    }
          

    

?>