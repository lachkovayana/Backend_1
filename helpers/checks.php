<?php
    function checkIfAdmin($token){
        $admin = "administrator";
        global $Link;
        $username = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc();
        if (!is_null($username)){
            $userId = $username['userId'];
            $userFromToken = $Link->query("SELECT roleId from users where userId='$userId'")->fetch_assoc();
            $roleId = $userFromToken['roleId'];
            $roleOfUser = $Link->query("SELECT name from roles where roleId='$roleId'")->fetch_assoc()['name'];
            if ($roleOfUser == $admin){
                return true;
            }
        }
        else {
            setHTTPStatus("500", "Something went wrong");
        }
        return false;
    }
    
    function checkIfIdOwner($token, $id){
        global $Link;
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