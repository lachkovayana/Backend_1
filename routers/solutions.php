<?php
    function route($method, $urlList, $data){

        include_once "./helpers/checks.php";
        global $Link;
        if (!$urlList[1]){
            if ($method == 'GET'){
                $taskFilter = $data->parameters['task'];
                $userFilter = $data->parameters['user'];

                if ($taskFilter && $userFilter){
                    $selectRequest = $Link->query("SELECT * from solutions where authorId='$userFilter' and taskId='$taskFilter'");
                }
                else if ($taskFilter && !$userFilter){
                    $selectRequest = $Link->query("SELECT * from solutions where taskId='$taskFilter'");
                }
                else if (!$taskFilter && $userFilter){
                    $selectRequest = $Link->query("SELECT * from solutions where authorId='$userFilter'");
                }
                else{
                    $selectRequest = $Link->query("SELECT * from solutions");
                }
                
                if ($selectRequest){
                    $solutions = [];
                    foreach ($selectRequest as $solution){
                        $solutions[] = $solution;
                    }    
                    echo json_encode($solutions);
                }
                else {
                    echo $Link->errno . $Link->error;
                    setHTTPStatus("500", "Something went wrong");
                }
            }
            else 
                setHTTPStatus("405", "Not allowed method");
        }
        else {
            if (is_numeric($urlList[1]) && $urlList[2] == "postmoderation"){
                if ($method == "POST"){
                    if (checkIfAdmin()){
                        $solId = $urlList[1];
                        $verdict = $data->body->verdict;
                        if (in_array($verdict, ["Pending", "OK", "Rejected"])){
                            $updateRequest = $Link->query("UPDATE solutions set verdict='$verdict' where id='$solId'");
                            if ($updateRequest){
                                $selectRequest = $Link->query("SELECT * from solutions");
                            
                                if ($selectRequest){
                                    $solutions = [];
                                    foreach ($selectRequest as $solution){
                                        $solutions[] = $solution;
                                    }    
                                    echo json_encode($solutions);
                                }
                                else {
                                    echo $Link->errno . $Link->error;
                                    setHTTPStatus("500", "Something went wrong");
                                }
                            }
                            else 
                            echo $Link->errno . $Link->error;
                                setHTTPStatus("500",  "Something went wrong");
                        }
                        else 
                            setHTTPStatus("400", "Incorrect verdict value");

                    }
                    else 
                        setHTTPStatus("403", "You are not an administrator");
                }
                else setHTTPStatus("405", "Method not allowed");
            }
            else 
                setHTTPStatus("404", "No such path");
        }
    }

  
?>