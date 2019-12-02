<?php

use App\Helpers\EstadoTransaccion;

if(! function_exists('devolverConsulta')){
    function devolverConsulta($array){
        $et = new EstadoTransaccion();
        if(is_array($array)){
            $array = array_filter((Array)$array);
    
            if (count($array)) {
                $et->mensaje = isset($array[0]->mensaje) ? $array[0]->mensaje : EstadoTransaccion::$procesoExitoso;
                $et->data = $array;
            }
            else {
                $et->mensaje = EstadoTransaccion::$noExistenDatos;
            }
        }

        return $et;
    }
}

if (!function_exists('devolverValidacion')) {
    function devolverValidacion($array){
        $et = new EstadoTransaccion();
        $array = json_decode($array, true);
        if(is_array($array)){
            $et->error = true;
            $et->status = EstadoTransaccion::$codeBadRequest;
            foreach ($array as $k => $v) {
                foreach ($v as $ke => $va) {
                    $et->mensaje.= $va.'</br>';
                }
            }
        }
        return $et;
    }
}

if (! function_exists('devolverError')) {
    function devolverError($clase,$mensaje,$error,$code){
        $et = new EstadoTransaccion();
        
        $et->clase   = $clase;
        $et->mensaje = $mensaje;
        $et->error   = $error;
        $et->status  = $code;
        $et->data    = [];
        return $et;
    }
}

if (! function_exists('devolverSubMenu')) {
    function devolverSubMenu(array $array,$key){
        $menu = []; 
        $cont = 0;
        $l = [];
    
        foreach ($array as $k => $v) {
            foreach ($v as $i => $d) {
                $menu [] = [
                    'url'  => $d['Ruta_opcion'] ?? NULL,
                    'name' => $d['Nombre_opcion'] ?? NULL,
                    'slug' => str_replace(' ','_',strtolower($d['Nombre_opcion']))  ?? NULL,
                    'i18n' => $d['Nombre_opcion'] ?? NULL
                ];
            }
            $l[] = [
                        'url'  => NULL,
                        'name' => $k,
                        'i18n' => $k,
                        'slug' => str_replace(' ','_',strtolower($k)) ?? NULL,
                        'icon' => $v[0]['iconoModulo'],
                        'submenu' => $menu
                    ];
            $menu = [];
        }
        
        return $l;
    }
}
