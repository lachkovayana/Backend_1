<?php





// доделать something went wrong при /topics/56


    function route($method, $urlList, $requestData){
        include_once './helpers/headers.php';
        include_once './helpers/checks.php';
        include_once './helpers/topics/topicsRequests.php';

        global $Link;
        
        if ($urlList[1]){
            
            include_once 'topics_topicId.php';
            route_1($method, $urlList, $requestData);
           
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