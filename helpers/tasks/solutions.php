<?php       
    function postSolution($data, $taskId){
        global $Link;
        $sourceCode = $data->body->sourceCode;
        $programmingLanguage = $data->body->programmingLanguage;

        if (!empty($sourceCode) && !empty($programmingLanguage) ){
            $token = substr(getallheaders()['Authorization'], 7);
            $userId = $Link->query("SELECT userId from tokens where value='$token'")->fetch_assoc()['userId'];
            $insertResult = $Link->query("INSERT into solutions (programmingLanguage, sourceCode, taskId, authorId) values ('$programmingLanguage', '$sourceCode', '$taskId', '$userId')");
            $id = $Link->insert_id;

            if ($insertResult)
                getOneSolution($id);
            else {
                echo $Link->errno;
                switch ($Link->errno){
                    case 1265:
                        setHTTPStatus("400". "Incorrect data. Try to use these programming languages : Python, C++, C#, Java");
                    default:
                        setHTTPStatus("500", "Something is wrong");
                }
            }
        }
        else {
            setHTTPStatus("400", "Incorrect data. Fill in all the fields");
        }
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