<?php
    header('Content-type: application/json');

    $link = mysqli_connect("127.0.0.1", "backend_demo_1", "password", "backend_demo_1");
    if (!$link){
        echo "Ошибка: Невозможно установить соединение с MySQL" . PHP_EOL;
        echo "Код ошибки: ", mysqli_connect_errno() . PHP_EOL;
        echo "Текст ошибки: ", mysqli_connect_error() . PHP_EOL;
        exit;
    }


    echo "Соединение с MySQL установлено!" , PHP_EOL;
    echo "Информация о сервере: " , mysqli_get_host_info($link) . PHP_EOL;

    $message=[];
    $message["users"] = [];
    $res = $link->query("SELECT id, name, login FROM users ORDER BY id ASC");
    if (!$res)
    {
        echo "Не удалось выполнить запрос: (" . $mysqli->errno . ") " . $mysqli->errno . PHP_EOL;
    }
    else
    {
        while ($row = $res->fetch_assoc())
        {
            $message["users"][] = [
                "id"=>$row['id'],
                "name"=>$row['name'],
                "login"=>$row['login']
            ];

        }
        echo json_encode($message);
    }
?>