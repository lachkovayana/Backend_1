<?php

    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/tasks/tasksRequests.php';

        global $Link;
        
        if ($urlList[1]){
            include_once 'tasks_taskId.php';
            route_1($method, $urlList, $requestData);
        }
        else{
            switch ($method){
                case 'GET':
                    if (checkToken())
                        getAllTasks($requestData);
                    else 
                        setHTTPStatus("403", "Authorization token is invalid");
                    break;
                case 'POST':
                    if (checkIfAdmin())
                        createNewTask($requestData);
                    else 
                        setHTTPStatus("403", "you are not an administrator");
                    break;
                default:
                    setHTTPStatus("405", "Method mot allow");
                    break;
            }
        }
   }
?>