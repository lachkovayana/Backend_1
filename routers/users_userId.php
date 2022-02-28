<?php

    function route_1($method, $urlList, $requestData){
        $id = intval($urlList[1]);
        if (is_numeric($id)){
            if ($urlList[2] && $urlList[2] == "role"){
                if (checkIfAdmin()){
                    if ($method == 'POST'){
                        setUserRole($id, $requestData);
                    }
                    else{
                        setHTTPStatus("405", "You can only use POST to /$urlList[0]//$urlList[1]//$urlList[2]");
                    }
                }
                else {
                    setHTTPStatus("403", "You are not an administrator");
                }
            }
            else { 
                switch($method){
                    case 'GET':
                        if (checkIfIdOwner($id) || checkIfAdmin())
                            getOneUserInfo($id);
                        else 
                            setHTTPStatus("403", "You are have not permission");
                        break;

                    case 'DELETE':
                        if (checkIfAdmin())
                            deleteUser($id);
                        else 
                            setHTTPStatus("403", "You are not an administrator");
                        break;

                    case 'PATCH':
                        if (checkIfAdmin())
                            updateUserInfo($id, $requestData);
                        else 
                            setHTTPStatus("403", "You are not an administrator");
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
?>