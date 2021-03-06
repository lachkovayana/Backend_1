<?php
    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';

        global $Link;

        if ($method == 'POST'){
            $token = substr(getallheaders()['Authorization'], 7);
            if (checkToken()){
                $logoutResult = $Link->query("DELETE FROM tokens WHERE value='$token'");
                if ($logoutResult){
                    echo "success logout";
                }
                else {
                    setHTTPStatus("500", "Some troubles with logout'");
                }
            }
            else {
                setHTTPStatus("403", "You are not authorized");
            }
        }
        else {
            setHTTPStatus("400", "You can only use POST to /$urlList[0]");
        }
    }
?>