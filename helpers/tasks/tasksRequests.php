<?php
    function getAllTasks($data){
        global $Link;
      
        $nameFilter = $data->parameters['name'];
        $topicFilter = $data->parameters['topicId'];

        if ($nameFilter && $topicFilter){
            // $tasksRequest = $Link->query("SELECT id, name, topicId from tasks where topicId='$topicFilter' and name='$nameFilter'");
            $tasksRequest = $Link->prepare('SELECT id, name, topicId from tasks where topicId=:topicFilter and name=:nameFilter');
            $tasksRequest->bindValue('topicFilter', $topicFilter, PDO::PARAM_INT);
            $tasksRequest->bindValue(':nameFilter', $nameFilter, PDO::PARAM_STR);
            $tasksRequest->execute();
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

    function updateTask($data, $id){
        global $Link;

        $name = $data->body->name;
        $topicId = $data->body->topicId;
        $description = $data->body->description;
        $price = $data->body->price;
        if ($name && !is_string($name)  || $topicId && !is_int($topicId) || 
            $description && !is_string($description) || $price && !is_int($price)) {

            setHTTPStatus("400", "Incorrect input data");
            return;
        }

       $error = 0;

        if ($topicId){
            $patchRequest = $Link->query("UPDATE  tasks set topicId='$topicId' where id='$id'");
            if ($Link->errno != 0) $error  = $Link->errno; 
        }
        if ($description){
            $patchRequest = $Link->query("UPDATE  tasks set description='$description' where id='$id'");
            if ($Link->errno != 0) $error  = $Link->errno; 
        }
        if ($price){
            $patchRequest = $Link->query("UPDATE  tasks set price='$price' where id='$id'");
            if ($Link->errno != 0) $error  = $Link->errno; 
        }
        if ($name){
            $patchRequest = $Link->query("UPDATE  tasks set name='$name' where id='$id'");
            if ($Link->errno != 0) $error  = $Link->errno; 
        } 
        
        if ($error){
            switch($error){
                case 1062:
                    setHTTPStatus("400", "Name '$name' is taken");
                    break;
                case 1452:
                    setHTTPStatus("400", "No topic with id $topicId");
                    break;
                    default:
                    setHTTPStatus("500", "Something went wrong");
                    break;
            }
        }
        else
           getOneTask($id);
    }


    function deleteTask($id){
        global $Link;
        $deleteRequest = $Link->query("DELETE from tasks where id='$id'");
        if ($deleteRequest)
            setHTTPStatus("200", "OK");
        else 
            setHTTPStatus("500", "Unexpected error");
    }
?>