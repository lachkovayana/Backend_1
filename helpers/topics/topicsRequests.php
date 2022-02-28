<?php
    function getAllTopics($data){
        global $Link;
      
        $nameFilter = $data->parameters['name'];
        $parentFilter = $data->parameters['parentId'];

        if ($nameFilter && $parentFilter){
            $topicsRequest = $Link->query("SELECT * from topics where parentId='$parentFilter' and name='$nameFilter'");
        }
        else if ($nameFilter && !$parentFilter){
            $topicsRequest = $Link->query("SELECT * from topics where name='$nameFilter'");
        }
        else if (!$nameFilter && $parentFilter){
            $topicsRequest = $Link->query("SELECT * from topics where parentId='$parentFilter'");
        }
        else{
            $topicsRequest = $Link->query("SELECT * from topics");
        }
        
        if ($topicsRequest){
            $topicsArray = [];
            foreach ($topicsRequest as $user){
                $topicsArray[] = $user;
            }    
            echo json_encode($topicsArray);
        }
        else {
            echo $Link->errno . $Link->error;
            setHTTPStatus("500", "Something went wrong");
        }
        
    }
    function getOneTopic($topicId){
        global $Link;
        $topic = $Link->query("SELECT * from topics where id='$topicId'")->fetch_assoc();
        if (!is_null($topic)){ 
            $topic['childs'] = getChilds($topic['id']);
            echo json_encode($topic);
        }
        else {
            setHTTPStatus("500", "Something went wrong");
        }
        
    }
    function getAllTopicsWChilds(){
        global $Link;
        $topicsRequest = $Link->query("SELECT * from topics");
        $topicsArray = [];
        foreach ($topicsRequest as $topic){
            $topic['childs'] = getChilds( $topic['id']);
            $topicsArray[] = $topic;
        }    
        echo json_encode($topicsArray);
        
    }

    function createNewTopic($data){
        global $Link;
        $name = $data->body->name;
        $parentId = $data->body->parentId;

        if (is_string($name) && !$parentId){
            $postRequest = $Link->query("INSERT into topics (name) values ('$name')");
        }
        else if (is_string($name) && ($parentId && is_integer($parentId))){
            $postRequest = $Link->query("INSERT into topics (name, parentId) values ('$name','$parentId')");
        }
        else{
            setHTTPStatus("400", "Incorrect input data");
            return;
        }
          
        if ($postRequest){
            getAllTopicsWChilds();
        }
        else{
            // echo $Link->errno . " : " . $Link->error . PHP_EOL;
            if ($Link->errno == 1062){
                setHTTPStatus("400", "Name '$name' is taken");
                return;
            }
            else if ($Link->errno == 1054){
                setHTTPStatus("400", "No such columns");
                return;
            }
            else if ($Link->errno == 1452){
                setHTTPStatus("400", "No task with id $parentId");
                return;
            }
            else{
                echo "Something went wrong";
            }
        }

    }

    function getChilds($id){
        global $Link;
        $topicsRequest = $Link->query("SELECT * from topics");
        $childsArray = [];
        foreach ($topicsRequest as $topic){
            if ( $topic["parentId"] && $id == $topic["parentId"]){
                array_push( $childsArray, $topic);
            }
        }
        return $childsArray;   
    }


    function deleteTopic($topicId){
        global $Link;
        $deleteResult = $Link->query("DELETE FROM topics WHERE id='$topicId'");
        
        if ($deleteResult){
            setHTTPStatus("200", "OK");
        }
        else {
            echo json_encode($Link->error) . PHP_EOL;
            setHTTPStatus("500", "Unexpected Error :(");
        }
        
    }
    function updateTopic($topicId, $data){
        global $Link;
        $name = $data->body->name;
        $parentId = $data->body->parentId;
        if ($name && !is_string($name) || $parentId && !is_int($parentId)) {
            setHTTPStatus("400", "Incorrect input data");
            return;
        }

        if ($name)
            $patchRequest = $Link->query("UPDATE  topics set name='$name' where id='$topicId'");

        if ($parentId)
            $patchRequest = $Link->query("UPDATE  topics set parentId='$parentId' where id='$topicId'");

        if ($patchRequest)
            getAllTopicsWChilds();
        
        else{
            // echo $Link->errno . " : " . $Link->error . PHP_EOL;
            if ($Link->errno == 1062){
                setHTTPStatus("400", "Name '$name' is taken");
                return;
            }
            else if ($Link->errno == 1054){
                setHTTPStatus("400", "No such columns");
                return;
            }
            else{
                setHTTPStatus("400", "Something went wrong");
            }
        }
    }



    function postChilds($parentId, $data){
        global $Link;
        
        foreach ($data->body as $childId){
            if (!is_int($childId)){
                setHTTPStatus("400", "Incorrect child id data type");
                return;
            }

            $patchRequest = $Link->query("UPDATE topics set parentId='$parentId' where id='$childId'");      
        }
        getOneTopic($parentId); 
        
        
    }
    function deleteChilds($parentId, $data){
        global $Link;
        
        foreach ($data->body as $childId){
            if (!is_int($childId)){
                setHTTPStatus("400", "Incorrect child id data type");
                return;
            }
            $deleteRequest = $Link->query("UPDATE topics set parentId=null where id='$childId'");   
        }
        getOneTopic($parentId);  
    }
?>