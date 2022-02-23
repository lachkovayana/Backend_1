<?php
    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';

        global $Link;

        if ($method == 'POST'){
            $token = substr(getallheaders()['Authorization'], 7);
            $logoutResult = $Link->query("DELETE FROM tokens WHERE value='$token'");
           if (!$logoutResult){
                // echo $Link->errno . " - " . $Link->error ;
                setHTTPStatus("500", "Some troubles with logout'");
           }
           else {
               echo "success logout";
           }
        }
        else {
            setHTTPStatus("400", "You can only use GET to /$urlList[0]");
        }
    }
?>