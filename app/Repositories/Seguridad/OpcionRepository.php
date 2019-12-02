<?php
namespace App\Repositories\Seguridad;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class OpcionRepository{
    
    public function listar($idOpcion,$opcion,$ruta,$icono,$idEstado){
        try {
            $r = DB::select('CALL SEG_CON_Opcion(?,?,?,?,?)',[
                $idOpcion,$opcion,$ruta,$icono,$idEstado
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idOpcion,$nombre,$ruta,$icono,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL ACA_MNT_Opcion(?,?,?,?,?,?,?)',[
                                $opcion,$idOpcion,$nombre,$ruta,$icono,$idEstado,$codUsuario
                            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
