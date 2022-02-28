<?php
    function checkIfAdmin(){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);

        if (checkToken($token)){
            $userId = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc()['userId'];
            $roleId = $Link->query("SELECT roleId from users where userId='$userId'")->fetch_assoc()['roleId'];
            $roleName = $Link->query("SELECT name from roles where roleId='$roleId'")->fetch_assoc()['name'];
            return $roleName == "administrator";
        }
        else 
            return false;
    }
    
    function checkIfIdOwner($id){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);
        
        $userId = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc()['userId'];
        if (!is_null($userId)){
            if ($id == $userId){
                return true;
            }
            return false;
        }
        else {
            setHTTPStatus("500", "Something went wrong");
        }
        return false;
    }

    function checkToken(){
        global $Link;
        $token = substr(getallheaders()['Authorization'], 7);
        
        if (empty($token))
            return false;

        $request= $Link->query("SELECT * from tokens where value='$token'")->fetch_assoc();
        if (is_null($request)){
            return false;
        }
        
        $validUntil = $request['validUntil'];
        
        $endTime = new Datetime($validUntil);
        $nowTime = new DateTime();
        return $endTime > $nowTime;
    }

    function checkIfRoleExist($roleId){
        global $Link;
        $request= $Link->query("SELECT * from roles where roleId='$roleId'")->fetch_assoc();
        if (is_null($request)){
            return false;
        }
        echo  date('Ymd'), date('Ymd', strtotime($request['validUntil']));
        return date('Ymd') == date('Ymd', strtotime($request['validUntil']));

    }

?>