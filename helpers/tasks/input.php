<?php
    function postInput($taskId){
        global $Link, $UploadsDir;
        
        $file = $_FILES['input'];
        if($file['type'] == "image/jpeg" || $file['type'] == "text/plain" ){
            $pathToUploads = "$UploadsDir"  . "/upload_". time() . "_" . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $pathToUploads);
        }
        else {
            setHTTPStatus("403", "Wrong file type");
        }
        
        $fileInsertResult =  $Link->query("UPDATE tasks set input='$pathToUploads' where id='$taskId'");
        if(!$fileInsertResult){
            echo $Link->error;
            setHTTPStatus("500", "DB saved failed");
        }
        else {
            setHTTPStatus("200", null);
            echo json_encode(["path" => $pathToUploads]);
        }
    }

?>