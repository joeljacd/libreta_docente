<?php
use App\Repositories\Mongo\Log;
// use Bugsnag\BugsnagLaravel\Facades\Bugsnag;

if(! function_exists('saveLog')){
    function saveLog($data){
        $log              = new Log();
        //$log->setData($array);
        $log->exception   = !empty($data['exception'])   ? $data['exception']  : '';
        $log->mensaje     = !empty($data['mensaje'])   ? $data['mensaje']      : '';
        $log->cod_empresa = !empty($data['cod_empresa']) ? $data['cod_empresa']: '';
        $log->cod_usuario = !empty($data['cod_usuario']) ? $data['cod_usuario']: '';
        $log->modulo      = !empty($data['modulo'])      ? $data['modulo']     : '';
        $log->opcion      = !empty($data['opcion'])      ? $data['opcion']     : '';
        $log->accion      = $data['accion'];
        $log->data        = $data['data'];
        $log->save();
    }
}

if(! function_exists('className')){
    function className($class=null){
        if(!empty($class)){
            $ruta=get_class($class);
            $array=explode("\\",$ruta);
            $nombre=$array[count($array)-1];
            return $nombre;
        }else{
            return false;
        }
        
    }
}

if(! function_exists('requestValidator')){
    function requestValidator($request,$reglas){
        try{
            $msg=null;
            $validacion = Validator::make($request, $reglas);
            if ($validacion->fails()){
                $msg=json_decode($validacion->messages(),true);
            }
            return $msg;
        }catch(\Exception $e){
            throw new \Exception ($e->getMessage());
        }        
    }
}

if(! function_exists('utf8_encode_deep')){
    function utf8_encode_deep(&$in) {
        $out = array();
        if (is_array($in)) { 
            foreach ($in as $key => $value) { 
                $out[$this->utf8_encode_deep($key)] = $this->utf8_encode_deep($value); 
            } 
        } elseif(is_string($in)) { 
            return utf8_encode($in); 
        } else { 
            return $in; 
        } 
        return $out; 
    }
}