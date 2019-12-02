<?php
namespace App\Repositories\Seguridad;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class ModuloRepository{
    
    public function listar($idModulo,$idAgrupacion,$nombre,$icono,$idEstado){
        try {
            $r = DB::select('CALL SEG_CON_Modulo(?,?,?,?,?)', [
                $idModulo,$idAgrupacion,$nombre,$icono,$idEstado
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idModulo,$idAgrupacion,$nombre,$icono,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL SEG_MNT_Modulo(?,?,?,?,?,?,?)',[
                $opcion,$idModulo,$idAgrupacion,$nombre,$icono,$idEstado,$codUsuario
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
