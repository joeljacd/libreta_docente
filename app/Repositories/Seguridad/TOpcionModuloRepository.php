<?php
namespace App\Repositories\Seguridad;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class TOpcionModuloRepository{
    
    public function listar($idSecModuloOpcion,$idModulo,$idOpcion,$idEstado){
        try {
            $r = DB::select('CALL SEG_CON_Modulo_Opcion(?,?,?,?)',[
                $idSecModuloOpcion,$idModulo,$idOpcion,$idEstado
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idSecModuloOpcion,$idModulo,$idOpcion,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL SEG_MNT_Modulo_Opcion(?,?,?,?,?,?)',[
                $opcion,$idSecModuloOpcion,$idModulo,$idOpcion,$idEstado,$codUsuario
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
