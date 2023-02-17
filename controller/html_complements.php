<?php
    function listado_intentos($array){
        $salida = '';
        if (is_array($array)) {
            foreach($array as $row){
                $salida.= '<button type="button" onclick="get_intento_usuario('.$row["intento"].');" class="list-group-item list-group-item-action">Intento #'.$row['intento'].'</button>';  
            }
        }
        return $salida;
    }

?>
