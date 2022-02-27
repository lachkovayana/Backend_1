<?php

    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/tasks/tasksRequests.php';

        global $Link;
        
        // if ($urlList[1]){
        //     $topicId = $urlList[1];
        //     if ($urlList[2]){
        //         if (){
        //             switch ($method){
        //                 case 'GET':
        //                     break;
        //                 case 'POST':
        //                     break;
        //                     case 'DELETE':
        //                     break;
        //                 default:
        //                     break;
        //             }
        //         }
        //         else{
        //             setHTTPStatus("404", "No such path");
        //         }
        //     }
        //     else {
        //         if (){
        //             switch ($method){
        //                 case 'GET':
        //                     break;
                        
        //                 case 'PATCH':
        //                     break;

        //                 case 'DELETE':
        //                     break;
        //             }
        //         }
        //         else {
        //             setHTTPStatus("400", "Incorrect id data type");
        //         }
        //     } 
        // }
        // else{
            switch ($method){
                case 'GET':
                    getAllTasks($requestData);
                    break;
                case 'POST':
                    if (checkIfAdmin()){
                        createNewTask($requestData);
                    }
                    break;
                default:
                    setHTTPStatus("405", "Method mot allow");
                    break;
            }
        }
//    }
?>