<?php
    function postInput(){
        global $Link, $UploadsDir;
        echo json_encode($_FILES['input']);
        
        $file = $_FILES['input'];
        if($file['type'] == "image/jpeg" || $file['type'] == "text/plain" ){
            $pathToUpload = "$UploadsDir"  . "/upload_". time() . "_" . $file['name'] ;
            move_uploaded_file($file['tmp_name'], $pathToUpload);
        }
        else {
            setHTTPStatus("403", "Wrong file type");
        }
        
        echo json_encode($pathToUpload);

    }

?>