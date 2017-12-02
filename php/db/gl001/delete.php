<?php 

    require $_SERVER['DOCUMENT_ROOT'] . '/hm/php/db/conn.php';

    //-------------------------------------------------------------------------------------------------
        // get values from ajax
    //-------------------------------------------------------------------------------------------------
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    } else { 
        echo "Не удалось получить ID: (" . $id . ") ";
    };

    //-------------------------------------------------------------------------------------------------
        // prepare queryes
    //-------------------------------------------------------------------------------------------------
    $query = "SELECT * 
                FROM gl001 
                WHERE id = ?";

    //-------------------------------------------------------------------------------------------------
        // execute query 0
    //-------------------------------------------------------------------------------------------------
    if (!($stmt = $mysqli->prepare($query))) { err2echo(10, "Выборка. ", $mysqli); }
    if (!$stmt->bind_param('i', $id)) { err2echo(11, "Выборка. ", $mysqli); }
    if (!$stmt->execute()) { err2echo(12, "Выборка. ", $mysqli); }
    $result = $stmt->get_result();
    $rows = []; 
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $data['data'] = $rows;

    //-------------------------------------------------------------------------------------------------
        // prepare queryes
    //-------------------------------------------------------------------------------------------------
    $query = "DELETE
                FROM gl001
                WHERE id = ?";

    //-------------------------------------------------------------------------------------------------
        // execute query 1
    //-------------------------------------------------------------------------------------------------
    if (!($stmt = $mysqli->prepare($query))) { err2echo(10, "Удаление. ", $mysqli); }
    if (!$stmt->bind_param('i', $id)) { err2echo(11, "Удаление. ", $mysqli); }
    if (!$stmt->execute()) { err2echo(12, "Удаление. ", $mysqli); }   

    $data['id'] = $id;
    $data['rows'] = mysqli_affected_rows($mysqli);
    
    //-------------------------------------------------------------------------------------------------
        // send result
    //-------------------------------------------------------------------------------------------------
    echo json_encode($data);
    
    //-------------------------------------------------------------------------------------------------
        // close connection
    //-------------------------------------------------------------------------------------------------
    $stmt->free_result();
    $stmt->close();

    $mysqli->close();

    function err2echo($id, $text, $conn) {
        switch ($id) {
            case 0 : echo $text . "Не удалось подключиться к MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error; break;
            case 10: echo $text . "Ошибка подготовки: (" . $conn->errno . ") " . $conn->error; break;
            case 11: echo $text . "Ошибка привязки: : (" . $conn->errno . ") " . $conn->error; break;
            case 12: echo $text . "Ошибка выполнения: (" . $conn->errno . ") " . $conn->error; break;
            case 13: echo $text . "Ошибка переменных: (" . $conn->errno . ") " . $conn->error; break;
            case 14: echo $text . "Ошибка выборки:  : (" . $conn->errno . ") " . $conn->error; break;
            case 15: echo $text . "Ошибка результата: (" . $conn->errno . ") " . $conn->error; break;
            default: break;
        }
    }

?>