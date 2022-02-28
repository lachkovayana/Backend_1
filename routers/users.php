<?php
    include_once './helpers/headers.php';
    include_once './helpers/checks.php';
    include_once './helpers/users/userDataRequests.php';

    
    function route($method, $urlList, $requestData){
        global $Link;
        
        if ($urlList[1]){
            include_once 'users_userId.php';
            route_1($method, $urlList, $requestData);
        }
        else {
            if (checkIfAdmin()){
                if ($method == 'GET'){
                getAllUsersInfo();
                }
                else {
                    setHTTPStatus("405", "You can only use GET to /$urlList[0]");
                }
            }
            else {
                setHTTPStatus("403", "You are not an administrator");
            }
        }
    }

          

    

?>