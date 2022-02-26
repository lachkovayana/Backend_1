<?php
    function getAllRoles(){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);
        if (!empty($token)  && checkIfTokenIsExist($token)){
            $rolesRequest = $Link->query("SELECT * from roles");
            if (!is_null($rolesRequest)){
                $rolesArray = [];
                foreach ($rolesRequest as $userId){
                    $rolesArray[] = $userId;
                }    
                echo json_encode($rolesArray);
            }
            else {
                setHTTPStatus("500", "Something went wrong");
            }
        }
        else {
            setHTTPStatus("403", "Authorization token are invalid");
        }
    }
    function getOneRole($roleId){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);
        if (!empty($token)  && checkIfTokenIsExist($token)){
            $role = $Link->query("SELECT * from roles where roleId='$roleId'")->fetch_assoc();
            if (!is_null($role)){ 
                echo json_encode($role);
            }
            else {
                setHTTPStatus("500", "Something went wrong");
            }
        }
        else {
            setHTTPStatus("403", "Authorization token are invalid");
        }
    }
?>