<?php 
    #---------------------------------------------------------------------------------
    # debug settings
    #---------------------------------------------------------------------------------
    if (true) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    #---------------------------------------------------------------------------------
    # 
    #---------------------------------------------------------------------------------
    require $_SERVER['DOCUMENT_ROOT'] . '/db/utils.php';
    #---------------------------------------------------------------------------------
    # check autorization
    #---------------------------------------------------------------------------------
    !isAuthorized() && die(err2echo(23, '', ''));
    #---------------------------------------------------------------------------------
    # connect to db
    #---------------------------------------------------------------------------------
    require $_SERVER['DOCUMENT_ROOT'] . '/db/conn.php';
    #---------------------------------------------------------------------------------
    # 
    #---------------------------------------------------------------------------------
    isset($_GET['data']["id"]) ? $id = $_GET['data']["id"] : die(err2echo(27, 'Удаление гостя. ', ''));
    #---------------------------------------------------------------------------------
    # 
    #---------------------------------------------------------------------------------
    $query = "SELECT * FROM gl001 WHERE id = ?";
    #
    !($stmt = $mysqli->prepare($query)) && die(err2echo(10, "Удаление гостя. ", $mysqli));
    !($stmt->bind_param('i', $id)) && die(err2echo(11, "Удаление гостя. ", $mysqli));
    !($stmt->execute()) && die(err2echo(12, "Удаление гостя. ", $mysqli));
    !($result = $stmt->get_result()) && die(err2echo(15, 'Удаление гостя', $mysqli));
    $rows = []; 
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    #
    $data['data'] = $rows;
    #---------------------------------------------------------------------------------
    # 
    #---------------------------------------------------------------------------------
    $query = "DELETE FROM gl001 WHERE id = ?";
    #
    !($stmt = $mysqli->prepare($query)) && die(err2echo(10, "Удаление гостя. ", $mysqli));  
    !($stmt->bind_param('i', $id)) && die(err2echo(11, "Удаление гостя. ", $mysqli));  
    !($stmt->execute()) && die(err2echo(12, "Удаление гостя. ", $mysqli));  
    #
    $data['id'] = $id;
    $data['rows'] = mysqli_affected_rows($mysqli);
    #
    $stmt->free_result();
    $stmt->close();
    #---------------------------------------------------------------------------------
    # 
    #---------------------------------------------------------------------------------
    $data['status'] = true;
    echo json_encode($data);
    #---------------------------------------------------------------------------------
    # 
    #---------------------------------------------------------------------------------
    $mysqli->close();
?>