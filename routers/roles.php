<?php
    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/roles/rolesRequests.php';

        global $Link;
        
        if ($method == 'GET'){
            $roleId = $urlList[1];
            if ($roleId){
                if (is_numeric($roleId) && checkIfRoleExist($roleId)){
                    getOneRole($roleId);
                }
                else {
                    setHTTPStatus("404", "No such path");
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