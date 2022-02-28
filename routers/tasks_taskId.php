<?php
    function route_1($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/tasks/tasksRequests.php';
        include_once './helpers/tasks/input.php';

        $taskId = $urlList[1];
        if (is_numeric($taskId)){
            switch ($method){
                case 'GET':
                    if (checkToken())
                        switch($urlList[2]){
                            case 'input':
                                // getInput($taskId);
                                break;
                            case 'output':
                                // getOutput($taskId);
                                break;
                            case null:
                                getOneTask($taskId);
                                break;
                            default:
                                setHTTPStatus("404", "No such path");
                        }
                    else 
                        setHTTPStatus("403", "Authorization token are invalid");
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
                    if(checkIfAdmin())
                        switch($urlList[2]){
                            case 'input':
                                postInput($taskId);
                                break;
                            case 'output':
                                // updateOutput($taskId);
                                break;
                            
                            default:
                                setHTTPStatus("404", "No such path");
                        }
                    else 
                        setHTTPStatus("403", "You are not an administrator");
                    break;
                case 'DELETE':
                    if(checkIfAdmin())
                        switch($urlList[2]){
                            case 'input':
                                // deleteInput();
                                break;
                            case 'output':
                                // deleteOutput($taskId);
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