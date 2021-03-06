<?php
    include_once './helpers/headers.php';
    include_once './helpers/validation.php';
    global $Link, $UploadsDir;
    function getData($method){
        $data = new stdClass();
        if ($method != 'GET'){
            $data->body = json_decode(file_get_contents('php://input'));
        }
        $data->parameters = [];
        $dataGet = $_GET;
        foreach ($dataGet as $key => $value) {
            if ($key != "q"){
                $data->parameters[$key] = $value;
            }
        }
        return $data;
    }
    function getMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }

    header('Content-type: application/json');
    
    $Link = mysqli_connect("127.0.0.1", "backend_demo_1", "password", "backend_demo_1");

    if (!$Link) {
        setHTTPStatus("500", "DB Connection Error: " . mysqli_connect_error());
        exit;
    }
    
    $url = isset($_GET['q']) ? $_GET['q'] : '';
    $url = rtrim($url, '/');
    $urlList = explode('/', $url);

    // if ($urlList[0] == 'API'){
        // $urlList = array_slice($urlList, 1);
        $router = $urlList[0];
        $method = getMethod();
        $requestData = getData($method);
        $UploadsDir = "uploads";


        if (file_exists( realpath(dirname(__FILE__)) . '/routers/' . $router . '.php' )) {
            include_once 'routers/' . $router . '.php';
            route($method, $urlList, $requestData);
        }
        else 
            setHTTPStatus("404", "No such path 2");
        
    // }
    // else
    //     setHTTPStatus("404", "No such path");

    
?>