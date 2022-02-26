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
?>