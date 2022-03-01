<?php
    function route_1($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/tasks/tasksRequests.php';
        include_once './helpers/tasks/files.php';
        include_once './helpers/tasks/solutions.php';

        $taskId = $urlList[1];
        if (is_numeric($taskId)){
            switch ($method){
                case 'GET':
                    if (checkToken())
                        switch($urlList[2]){
                            case 'input':
                                getFilePath($taskId, 'input');
                                break;
                            case 'output':
                                getFilePath($taskId, 'output');
                                break;
                            case null:
                                getOneTask($taskId);
                                break;
                            default:
                                setHTTPStatus("404", "No such path");
                        }
                    else 
                        setHTTPStatus("403", "Authorization token is invalid");
                    break;
                case 'PATCH':
                    if(checkIfAdmin()){
                        if (is_null($urlList[2]))
                            updateTask($requestData, $taskId);
                        else
                            setHTTPStatus("405", "Not allowed method");
                    }
                    break;
                case 'POST':
                    if(checkIfAdmin() || ($urlList[2] == 'solution' && checkToken()))
                        switch($urlList[2]){
                            case 'input':
                                postFile($taskId, 'input');
                                break;
                            case 'output':
                                postFile($taskId, 'output');
                                break;
                            case 'solution':
                                postSolution($requestData, $taskId);
                                break;
                            default:
                                setHTTPStatus("404", "No such path");
                        }
                    else 
                        setHTTPStatus("403", "Authorization token is invalid");
                    break;
                case 'DELETE':
                    if(checkIfAdmin())
                        switch($urlList[2]){
                            case 'input':
                                deleteFile($taskId, 'input');
                                break;
                            case 'output':
                                deleteFile($taskId, 'output');
                                break;
                            case null:
                                deleteTask($taskId);
                                break;
                            default:
                                setHTTPStatus("404", "No such path");
                        }
                    else 
                        setHTTPStatus("403", "You are not an administrator");
                    break;
                default:
                    break;
            }
        }
        else{
            setHTTPStatus("404", "Incorrect type of task ID");
            return;
        }
    }
?>