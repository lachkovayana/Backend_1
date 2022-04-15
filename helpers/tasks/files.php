<?php
    function postFile($taskId, $column){
        global $Link, $UploadsDir;
        
        $file = $_FILES[$column];
        // echo $file['type'];
        if($file['type'] == "text/plain" ){

            $pathToUploads = "$UploadsDir"  . "/" . $column . "_". time() . "_" . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $pathToUploads);
        }
        else {
            setHTTPStatus("403", "Wrong file type", $file['type'] );
            return;
        }
        
        $fileInsertResult = $Link->query("UPDATE tasks set $column='$pathToUploads' where id='$taskId'");
        if(!$fileInsertResult){
            echo $Link->error;
            setHTTPStatus("500", "DB saved failed");
        }
        else {
            setHTTPStatus("200", null);
            getOneTask($taskId);
        }
    }

    function getFilePath($taskId, $column){
        global $Link;
        $filePath =  $Link->query("SELECT $column from tasks where id='$taskId'")->fetch_assoc()[$column];

        setHTTPStatus("200", null);
        echo json_encode(["path" => $filePath]);
    }

    function deleteFile($taskId, $column){
        global $Link;
        $deleteResult = $Link->query("UPDATE tasks set $column=null where id='$taskId'");
        if(!$deleteResult){
            echo $Link->error;
            setHTTPStatus("500", "Delete failed");
        }
        else {
            setHTTPStatus("200", "OK");
        }
    }
?>