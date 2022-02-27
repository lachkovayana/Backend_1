<?php
    function getAllTasks(){
        global $Link;
      
        $tasksRequest = $Link->query("SELECT * from tasks");
        if (!is_null($tasksRequest)){
            $tasksArray = [];
            foreach ($tasksRequest as $task){
                $tasksArray[] = $task;
            }    
            echo json_encode($tasksArray);
        }
        else {
            setHTTPStatus("500", "Something went wrong");
        }
    }

    function createNewTask($data){
        global $Link;

        $name = $data->body->name;
        $description = $data->body->description;
        $price = $data->body->price;
        $topicId = $data->body->topicId;
        if ($name && $description && $price && $topicId)
            $postRequest = $Link->query("INSERT into tasks (name, description, price, topicId) values ('$name','$description', '$price', '$topicId')");
        else 
            setHTTPStatus("400", "Data is not complete");
        
        if ($postRequest){
          setHTTPStatus("200", "OK");
        }
        else{
            echo $Link->errno . " : " . $Link->error . PHP_EOL;
            if ($Link->errno == 1062){
                setHTTPStatus("400", "Name '$name' is taken");
                return;
            }
            else if ($Link->errno == 1054){
                setHTTPStatus("400", "No such columns");
                return;
            }
            else{
                echo "Something went wrong";
            }
        }

    }


?>