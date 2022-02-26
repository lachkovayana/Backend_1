<?php
    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/topics/topicsRequests.php';

        global $Link;
        
        if ($urlList[1]){
            $topicId = $urlList[1];
            if (is_numeric($topicId)){
                getOneTopic($topicId);
            }
            else {
                setHTTPStatus("400", "Incorrect id data type");
            }
        }
        else{
            switch ($method){
                case 'GET':
                    getAllTopics("topics");
                    break;
                case 'POST':
                    if (checkIfAdmin()){
                        createNewTopic($requestData);
                    }
                    else {
                        setHTTPStatus("403", "You are not an administrator");
                    }
                    break;
                default:
                    setHTTPStatus("405", "Method mot allow");
                    break;
            }
        }
   }
?>