<?php
    function route_1($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/tasks/tasksRequests.php';
        
        $taskId = $urlList[1];
        switch ($urlList[2]){
            case 'input':

                break;
            case 'output':

                break;
            case null:
                if (is_numeric($taskId)){
                    switch ($method){
                        case 'GET':
                        if (checkToken())
                            getOneTask($taskId);
                        break;
                    case 'POST':
                        break;
                        case 'DELETE':
                        break;
                        default:
                        break;
                    }
                }
                else{
                    setHTTPStatus("404", "Incorrect type of task ID");
                }
                break;
            default:
                setHTTPStatus("404", "No such path");
                break;
        }
    }
?>