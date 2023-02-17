<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cahce");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

$request = $_REQUEST["request"];
include_once('html_complements.php');
switch ($request) {
    case 'login':
        $nombre = $_REQUEST["nombre"];
        ingresa_usuario($nombre);
        break;
    case 'sesion':
        $codigo = $_REQUEST["codigo"];
        $nombre = $_REQUEST["nombre"];
        inicio_sesion($codigo,$nombre);
        break;
    case 'intento':
        $usuario = $_REQUEST["usuario"];
        $ingresado = $_REQUEST["ingresado"];
        $sumatoria = $_REQUEST["sumatoria"];
        $primos = $_REQUEST["primos"];
        genera_intento($usuario,$ingresado,$sumatoria,$primos);
        break;
    case 'get_intentos':
        $usuario = $_REQUEST["usuario"];
        get_intentos_usuario($usuario);
        break;
    case 'get_intento_usuario':
        $usuario = $_REQUEST["usuario"];
        $intento = $_REQUEST["intento"];
        get_intento_usuario($usuario,$intento);
        break;
    default:
        
        break;
}

function ingresa_usuario($nombre){
    $fichero = 'Usuarios.txt';
    $fichero_texto = fopen ($fichero, "r");
    $i = 0;
    while (!feof($fichero_texto)) {
        $txt = trim(fgets($fichero_texto));
        if (!empty($txt)) {
            $fragmento = explode(':' , $txt);
            $arr_filas[$i]['codigo'] = intval($fragmento[0]);
            $arr_filas[$i]['nombre'] = trim($fragmento[1]);
            $i++;            
        }
    }
    $i--;
    fclose($fichero_texto);
    //var_dump($arr_filas);

    $nuevo_codigo = $arr_filas[$i]['codigo'];
    $codigo = $nuevo_codigo+1;
    $i++;
    $arr_filas[$i]['codigo'] = $codigo;
    $arr_filas[$i]['nombre'] = $nombre;    
    
    $file = fopen($fichero, "w");
    foreach($arr_filas as $row){
        if ($row['codigo'] != '' && $row['nombre'] != '') {
            fwrite($file, $row['codigo'].':'.$row['nombre'] . PHP_EOL);
        }
    }
    fclose($file);


    if (is_array($arr_filas)) {
        $arr_respuesta = array(
            "status" => true,
            "codigo" => $codigo,
            "nombre" => $nombre,
            "message" => "Registro guardado satisfactoriamente...!"
        );
        
    }else {
        $file = fopen($fichero, "w");
        fwrite($file, "1:$nombre" . PHP_EOL);
        fclose($file);
        $arr_respuesta = array(
            "status" => false,
            "data" => [],
            "message" => "Se registro satisfactoriamente...!"
        );
    }
    echo json_encode($arr_respuesta);
}

function inicio_sesion($codigo,$nombre){
    session_start();
    $_SESSION['codigo']  = $codigo;
    $_SESSION['usuario'] = $nombre;

    $arr_respuesta = array(
        "status" => false,
        "message" => "Inicio de sesion"
    );
    echo json_encode($arr_respuesta);
}

function genera_intento($usuario,$ingresado,$sumatoria,$primos){
    /*  orden para insertar en el bloc de notas
        codigo de usuario:numero de intento:numero ingresado:sumatoria:numeros primos    */
    $fichero = 'Intentos.txt';
    $fichero_texto = fopen ($fichero, "r");
    $i = 0;
    while (!feof($fichero_texto)) {
        $txt = trim(fgets($fichero_texto));
        if (!empty($txt)) {
            $fragmento = explode(':' , $txt);
            $arr_filas[$i]['usuario'] = intval($fragmento[0]);
            $arr_filas[$i]['intento'] = trim($fragmento[1]);
            $arr_filas[$i]['ingresado'] = trim($fragmento[2]);
            $arr_filas[$i]['sumatoria'] = trim($fragmento[3]);
            $arr_filas[$i]['primos'] = trim($fragmento[4]);
            $i++;            
        }
    }
    $i--;
    fclose($fichero_texto);

    $nuevo_codigo = $arr_filas[$i]['intento'];
    $codigo = $nuevo_codigo+1;
    $i++;
    $arr_filas[$i]['usuario'] = $usuario;
    $arr_filas[$i]['intento'] = $codigo;
    $arr_filas[$i]['ingresado'] = $ingresado;    
    $arr_filas[$i]['sumatoria'] = $sumatoria;    
    $arr_filas[$i]['primos'] = $primos;    
    
    $file = fopen($fichero, "w");
    foreach($arr_filas as $row){
        if ($usuario != '') {
            fwrite($file, $row['usuario'].':'.$row['intento'].':'.$row['ingresado'].':'.$row['sumatoria'].':'.$row['primos'] . PHP_EOL);
        }
    }
    fclose($file);
    
    if (is_array($arr_filas)) {
        $arr_respuesta = array(
            "status" => true,
            "intento" => $codigo,
            "message" => "Registro guardado"
        );
    }else {
        $arr_respuesta = array(
            "status" => false,
            "message" => "Error"
        );
    }
    echo json_encode($arr_respuesta);
}

function get_intentos_usuario($usuario){
    $fichero = 'Intentos.txt';
    $fichero_texto = fopen ($fichero, "r");
    $i = 0;
    while (!feof($fichero_texto)) {
        $txt = trim(fgets($fichero_texto));
        if (!empty($txt)) {
            $fragmento = explode(':' , $txt);
            if ($fragmento[0] == $usuario) {
                $arr_filas[$i]['intento'] = trim($fragmento[1]);
                $arr_filas[$i]['ingresado'] = trim($fragmento[2]);
                $arr_filas[$i]['sumatoria'] = trim($fragmento[3]);
                $arr_filas[$i]['primos'] = trim($fragmento[4]);
                $i++;            
            }
        }
    }
    $i--;
    fclose($fichero_texto);

    if (is_array($arr_filas)) {
        $arr_respuesta = array(
            "status" => true,
            "lista" => listado_intentos($arr_filas),
            "message" => "Registro guardado"
        );
    }else {
        $arr_respuesta = array(
            "status" => false,
            "message" => "Error"
        );
    }
    echo json_encode($arr_respuesta);
}

function get_intento_usuario($usuario,$intento){
    $fichero = 'Intentos.txt';
    $fichero_texto = fopen ($fichero, "r");
    $i = 0;
    while (!feof($fichero_texto)) {
        $txt = trim(fgets($fichero_texto));
        if (!empty($txt)) {
            $fragmento = explode(':' , $txt);
            if ($fragmento[0] == $usuario && $fragmento[1] == $intento) {
                $ingresado = trim($fragmento[2]);
                $sumatoria = trim($fragmento[3]);
                $primos = trim($fragmento[4]);
                $i++;            
            }
        }
    }
    $i--;
    fclose($fichero_texto);

    if ($ingresado != 0) {
        $arr_respuesta = array(
            "status" => true,
            "numero" => $ingresado,
            "sumatoria" => $sumatoria,
            "primos" => $primos,
            "message" => "Registro regresado"
        );
    }else {
        $arr_respuesta = array(
            "status" => false,
            "message" => "Error"
        );
    }
    echo json_encode($arr_respuesta);
}

?>