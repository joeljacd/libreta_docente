<?php
namespace App\Repositories\Academico;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class FacultadRepository{
    
    public function listar($idFacultad,$codigo,$nombre,$abreviatura,$idEstado){
        try {
            $r = DB::select('CALL ACA_CON_Facultad(?,?,?,?,?)', [
                $idFacultad,$codigo,$nombre,$abreviatura,$idEstado
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idFacultad,$codigo,$nombre,$abreviatura,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL ACA_MNT_Facultad(?,?,?,?,?,?,?)',[
                $opcion,$idFacultad,$codigo,$nombre,$abreviatura,$idEstado,$codUsuario
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
