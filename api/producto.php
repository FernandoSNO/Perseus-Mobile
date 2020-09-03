<?php
require_once('api.php');

$resultado = NULL;
$parametros = NULL;

$request = resolveRequest();

if ($request['method']==='get')
{
	$parametros = $_GET;
    $mysqli = new mysqli("localhost", "root", "", "perseus", 3306);
    if ($mysqli->connect_errno) {
        echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    if (isset($parametros['id_producto']))
    {
        $query = "SELECT * FROM productos WHERE id_producto=".$parametros['id_producto'];
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
        json_response($data, 200);
    } else {
        json_response($data, 200);
    }
}


if ($request['method']==='post')
{
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
			json_response($respuesta, 200);
		}else {
			$respuesta = [
				"status"=>200,
				"response" => 'nok'
			];
			json_response($respuesta, 200);
		}
	} else {
		$respuesta = [
			"status"=>200,
			"response" => 'nok, verificar formulario'
		];
		json_response($respuesta, 200);
	}
}
