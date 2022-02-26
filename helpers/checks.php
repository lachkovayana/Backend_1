<?php
    function checkIfAdmin(){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);
        
        $username = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc();
        if (!is_null($username)){
            $userId = $username['userId'];
            $userFromToken = $Link->query("SELECT roleId from users where userId='$userId'")->fetch_assoc();
            $roleId = $userFromToken['roleId'];
            $roleOfUser = $Link->query("SELECT name from roles where roleId='$roleId'")->fetch_assoc()['name'];
            if ($roleOfUser == "administrator"){
                return true;
            }
        }
        else {
            setHTTPStatus("500", "Something went wrong");
        }
        return false;
    }
    
    function checkIfIdOwner($id){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);

        $username = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc();
        if (!is_null($username)){
            if ($id == $username['userId']){
                return true;
            }
            return false;
        }
        else {
            setHTTPStatus("500", "Something went wrong");
        }
        return false;
    }

    function checkIfTokenIsExist($token){
        global $Link;
        $request= $Link->query("SELECT * from tokens where value='$token'")->fetch_assoc();
        return !is_null($request);
    }

    function checkIfRoleExist($roleId){
        global $Link;
        $request= $Link->query("SELECT name from roles where roleId='$roleId'")->fetch_assoc();
        return !is_null($request);
    }

?>