<?php
    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';

        global $Link;

        if ($method == 'POST'){
            $token = substr(getallheaders()['Authorization'], 7);
            if (!empty($token)){
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
                setHTTPStatus("403", "You are not authorized");
            }
        }
        else {
            setHTTPStatus("400", "You can only use POST to /$urlList[0]");
        }
    }
?>