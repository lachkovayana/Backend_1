<?php

    function getAllTopics(){
        global $Link;
      
        $topicsRequest = $Link->query("SELECT * from topics");
        if (!is_null($topicsRequest)){
            $topicsArray = [];
            foreach ($topicsRequest as $userId){
                $topicsArray[] = $userId;
            }    
            echo json_encode($topicsArray);
        }
        else {
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
            else{
                echo "Something went wrong";
            }
        }
        

    }

    function getChilds($id){
        global $Link;
        $topicsRequest = $Link->query("SELECT * from topics");
        $someArr = [];
        foreach ($topicsRequest as $topic){
            if ( $topic["parentId"] && $id == $topic["parentId"]){
                array_push( $someArr, $topic);
            }
        }
        return $someArr;   
    }


    function deleteTopic($topicId){
        global $Link;
        if (checkIfAdmin()){
            $deleteResult = $Link->query("DELETE FROM topics WHERE id='$topicId'");
            
            if ($deleteResult){
               setHTTPStatus("200", "OK");
                // echo "OK";
            }
            else {
                echo json_encode($Link->error) . PHP_EOL;
                setHTTPStatus("500", "Unexpected Error :(");
            }
        }
        else{
            setHTTPStatus("403", "You do not have permission to delete this user");
        }
    }
    function updateTopic($topicId, $data){
        global $Link;
        $name = $data->body->name;
        $parentId = $data->body->parentId;
        if ($name && is_string($name)){
            $patchRequest = $Link->query("UPDATE  topics set name='$name' where id='$topicId'");
        }
        else if ($parentId && is_integer($parentId)){
            $patchRequest = $Link->query("UPDATE  topics set parentId='$parentId' where id='$topicId'");
        }
        else{
            setHTTPStatus("400", "Incorrect input data");
            return;
        }

        if ($patchRequest){
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
            else{
                echo "Something went wrong";
            }
        }
    }



    function postChilds($id, $data){
        global $Link;
        
        foreach ($data->body as $elemId){
            //validation for elemId
            $patchRequest = $Link->query("UPDATE topics set parentId='$id' where id='$elemId'");
            //check if request is right            
        }
        
    }
    function deleteChilds($id, $data){
        global $Link;
        
        foreach ($data->body as $elemId){
            //validation for elemId
            $deleteRequest = $Link->query("UPDATE topics set parentId=null where id='$elemId'");
            //check if request is right            
        }
    }
?>