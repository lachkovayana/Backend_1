<?php
    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/roles/rolesRequests.php';

        global $Link;
        
        if ($method == 'GET'){
            if ($urlList[1]){
                $roleId = $urlList[1];
                if (is_numeric($roleId) ){
                    getOneRole($roleId);
                }
                else {
                    setHTTPStatus("400", "Incorrect id data type");
                }
            }
            else{
                    getAllRoles();
                }
            }
        else {
            setHTTPStatus("405", "You can only use GET to /$urlList[0]");
        }
   }
?>