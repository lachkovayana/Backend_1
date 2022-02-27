<?php
    function route_1($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/topics/topicsRequests.php';
        
        $topicId = $urlList[1];
        if ($urlList[2]){
            if ($urlList[2] == 'childs'){
                switch ($method){
                    case 'GET':
                        echo json_encode(getChilds($topicId));
                        break;
                    case 'POST':
                        postChilds($topicId, $requestData);
                        break;
                        case 'DELETE':
                        deleteChilds($topicId, $requestData);
                        break;
                    default:
                        break;
                }
            }
            else{
                setHTTPStatus("404", "No such path");
            }
        }
        else {
            if (is_numeric($topicId)){
                switch ($method){
                    case 'GET':
                        getOneTopic($topicId);
                        break;
                    
                    case 'PATCH':
                        if (checkIfAdmin()){
                            updateTopic($topicId, $requestData);
                        }
                        else {
                            setHTTPStatus("403", "You are not an administrator");
                        }
                        break;

                    case 'DELETE':
                        deleteTopic($topicId);
                        break;
                }
            }
            else {
                setHTTPStatus("400", "Incorrect id data type");
            }
        } 
    }
?>