<?php       
    function postSolution($data, $taskId){
        global $Link;
        $sourceCode = $data->body->sourceCode;
        $programmingLanguage = $data->body->programmingLanguage;

        $token = substr(getallheaders()['Authorization'], 7);
        $userId = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc()['userId'];
        $insertResult = $Link->query("INSERT into solutions (programmingLanguage, sourceCode, taskId, authorId) values ('$programmingLanguage', '$sourceCode', '$taskId', '$userId')");
        $id = $Link->insert_id;

        if ($insertResult)
            getOneSolution($id);
        else 
            echo $Link->error;
            // setHTTPStatus("500", "Something is wrong 2");

    }


    function getOneSolution($id){
        global $Link;
        $selectResult = $Link->query("SELECT * from solutions where id='$id'")->fetch_assoc();
        if ($selectResult)
            echo json_encode($selectResult);
        
        else
            setHTTPStatus("500", "Something is wrong");
    }
?>