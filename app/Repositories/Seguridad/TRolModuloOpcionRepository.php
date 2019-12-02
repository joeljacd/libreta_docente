<?php
namespace App\Repositories\Seguridad;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class TRolModuloOpcionRepository{
    
    public function listar($idSec,$idRol,$idModuloOpcion,$idModulo,$idOpcion,$idEstado){
        try {
            $r = DB::select('CALL SEG_CON_Rol_Modulo_Opcion(?,?,?,?,?,?)',[
                $idSec,$idRol,$idModuloOpcion,$idModulo,$idOpcion,$idEstado
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idSec,$idRol,$idModuloOpcion,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL SEG_MNT_Rol_Modulo_Opcion(?,?,?,?,?,?)',[
                    $opcion,$idSec,$idRol,$idModuloOpcion,$idEstado,$codUsuario
                ]);

                return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
