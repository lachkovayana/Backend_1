<?php
    function getAllRoles(){
        global $Link;
            $rolesRequest = $Link->query("SELECT * from roles");
            if ($rolesRequest){
                $rolesArray = [];
                foreach ($rolesRequest as $userId){
                    $rolesArray[] = $userId;
                }    
                echo json_encode($rolesArray);
            }
            else {
                setHTTPStatus("404", "No roles yet");
            }
        
    }
    function getOneRole($roleId){
        global $Link;
       
        $role = $Link->query("SELECT * from roles where roleId='$roleId'")->fetch_assoc();
        if ($role){ 
            echo json_encode($role);
        }
        else {
            setHTTPStatus("404", "Role $roleId not found");
        }
        
    }
?>