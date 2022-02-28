<?php
    function route_1($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/topics/topicsRequests.php';

        $topicId = $urlList[1];

        if (is_numeric($topicId)){

            if ($urlList[2]){
                if ($urlList[2] == 'childs'){
                    switch ($method){
                        case 'GET':
                            echo json_encode(getChilds($topicId));
                            break;
                        case 'POST':
                            if (checkIfAdmin())
                                postChilds($topicId, $requestData);
                            else 
                                setHTTPStatus("403", "You are not an administrator");
                            break;
                            case 'DELETE':
                            if (checkIfAdmin())
                                deleteChilds($topicId, $requestData);
                            else 
                                setHTTPStatus("403", "You are not an administrator");
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
                switch ($method){
                    case 'GET':
                        getOneTopic($topicId);
                        break;
                    
                    case 'PATCH':
                        if (checkIfAdmin())
                            updateTopic($topicId, $requestData);
                        else 
                            setHTTPStatus("403", "You are not an administrator");
                        break;

                    case 'DELETE':
                        if (checkIfAdmin())
                            deleteTopic($topicId);
                        else 
                            setHTTPStatus("403", "You are not an administrator");
                        break;
                }
            }
        }
        else 
            setHTTPStatus("400", "Incorrect id data type");
            
    }
?>