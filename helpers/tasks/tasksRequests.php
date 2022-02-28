<?php
    function getAllTasks($data){
        global $Link;
      
        $nameFilter = $data->parameters['name'];
        $topicFilter = $data->parameters['topicId'];

        if ($nameFilter && $topicFilter){
            $tasksRequest = $Link->query("SELECT id, name, topicId from tasks where topicId='$topicFilter' and name='$nameFilter'");
        }
        else if ($nameFilter && !$topicFilter){
            $tasksRequest = $Link->query("SELECT id, name, topicId from tasks where name='$nameFilter'");
        }
        else if (!$nameFilter && $topicFilter){
            $tasksRequest = $Link->query("SELECT id, name, topicId from tasks where topicId='$topicFilter'");
        }
        else{
            $tasksRequest = $Link->query("SELECT id, name, topicId from tasks");
        }
        
        if ($tasksRequest){
            $tasksArray = [];
            foreach ($tasksRequest as $task){
                $tasksArray[] = $task;
            }    
            echo json_encode($tasksArray);
        }
        else {
            echo $Link->errno . $Link->error;
            setHTTPStatus("500", "Something went wrong");
        }
    }

    function getOneTask($id){
        global $Link;
        $task = $Link->query("SELECT id, name, topicId, description, price, isDraft from tasks where id='$id'")->fetch_assoc();
        if ($task){ 
            echo json_encode($task);
        }
        else {
            setHTTPStatus("404", "Task $id not found");
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