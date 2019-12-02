<?php
namespace App\Repositories\Seguridad;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class RolRepository{
    
    public function listar($idRol,$nombre,$abreviatura,$idEstado){
        try {
            $r = DB::select('CALL SEG_CON_Rol(?,?,?,?)',[
                $idRol,$nombre,$abreviatura,$idEstado
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idRol,$nombre,$abreviatura,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL SEG_MNT_Rol(?,?,?,?,?,?)',[
                    $opcion,$idRol,$nombre,$abreviatura,$idEstado,$codUsuario
                ]);

                return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
