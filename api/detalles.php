<?php

$resultado = NULL;
$parametros = NULL;


if ($_SERVER['REQUEST_METHOD'] == "GET")
{

    $id=$_GET['id'];
    $mysqli = new mysqli("localhost", "root", "", "perseus", 3306);
    if ($mysqli->connect_errno) {
        echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    if (isset($id))
    {
        $query = "SELECT * FROM productos WHERE id_producto=".$id;
        $resultado = $mysqli->query($query);
    } else {
        $query = "SELECT * FROM productos ORDER BY id_producto ASC";
        $resultado = $mysqli->query("SELECT * FROM productos ORDER BY id_producto ASC");
    }

    $data = [];
    if ($resultado->num_rows > 0)
    {
        while ($fila = $resultado->fetch_assoc())
        {
            $data[] = $fila;
        }

        $respuesta = [
            "status"=> 200,
            "response" => $data
        ];
        
        echo json_encode($respuesta);
    } else {
        $respuesta = [
            "status"=> 200,
            "response" => null
        ];
        
        echo json_encode($respuesta);
    }
    
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json')
    {
        $requestDataJSON = file_get_contents('php://input');
        $parametros = json_decode($requestDataJSON, TRUE);
    } else {
        $parametros = $_POST;
    }

    $mysqli = new mysqli("localhost", "root", "", "perseus", 3306);
    if ($mysqli->connect_errno) {
        echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    
    // validar el texto que llega para prevenir inyecciones de sql
    // $mysqli->error
    if (isset($parametros['name']) && isset($parametros['price']) && isset($parametros['category']) && isset($parametros['image']))
    {
        $insertQuery = "INSERT INTO productos (nombre, precio, categoria, imagen) VALUES (";
        $insertQuery .= "'".$parametros['name']."'".','.$parametros['price'].','.$parametros['category'].','.$parametros['image'].')';

        if($mysqli->query($insertQuery) === TRUE)
        {
            $respuesta = [
                "status"=>200,
                "response" => 'ok'
            ];
            echo json_encode($respuesta);
        }else {
            $respuesta = [
                "status"=>200,
                "response" => 'nok'
            ];
            echo json_encode($respuesta);
        }
    } else {
        $respuesta = [
            "status"=>200,
            "response" => 'nok, verificar formulario'
        ];
        echo json_encode($respuesta);
    }
    
}
