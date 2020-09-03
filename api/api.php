<?php
    /*
      $response: recibe todo tipo de dato
      $code: recibe como parametro un integer (representa el codigo http)
      retorna json
    */
     function json_response($response = null, $code = 200)
     {
        // clear headers
        header_remove();
        // set del codigo de respuestas
        http_response_code($code);
        // forzar la cache del header
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // interpretación de formato json
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            403 => 'forbidden',
            404 => 'Not Found',
            405 => 'Method not Allowed',
            503 => 'Service Unavailable',
            500 => '500 Internal Server Error'
            );
        // ok, validación error, o falla
        header('Status: '.$status[$code]);
        // retorna json
        echo json_encode(array(
            'status' => $code < 300,
            'response' => $response
            ), true);
    }
    

    /*
      $allowed_methods: recibe un array de strings con los metodos http que acepta
      retorna array asociativo
      ["params"=>"parametros recibidos en el request",
      "method"=>"tipo de metodo:ej('get,post,put,delete')"]
    */
    function resolveRequest($allowed_methods=['GET','POST','PUT','DELETE'])
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        if(in_array($request_method, $allowed_methods))
        {
            $request_type = strtolower($request_method);
            $getRequestData = function(){
                $params = [];
                $data = fopen("php://input", "r");
                $str = urldecode(stream_get_contents($data));
                fclose($data);
                foreach (explode('&', $str) as $value) {
                    $param = explode('=', $value);
                    $params[$param[0]] = $param[1];
                }
                return $params;
            };
            $getPostData = function(){
                $params = null;
                if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json')
                {
                    $requestDataJSON = file_get_contents('php://input');
                    $params = json_decode($requestDataJSON, TRUE);
                } else {
                    $params = $_POST;
                }
                return $params;
            };
            switch ($request_type)
            {
                case 'get':
                    return ["params"=>$_GET, "method"=>$request_type];
                    break;
                case 'post':
                    return ["params"=>$getPostData(), "method"=>$request_type];
                    break;
                case 'put':
                    return ["params"=>$getRequestData(), "method"=>$request_type];
                    break;
                case 'delete':
                    return ["params"=>$getRequestData(), "method"=>$request_type];
                    break;
                default:
                    return http_response_code(405);
                    break;
            }
        }else{
            return http_response_code(405);
        }
    }
