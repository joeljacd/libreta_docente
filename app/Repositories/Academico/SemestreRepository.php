<?php
namespace App\Repositories\Academico;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class SemestreRepository{
    
    public function listar($idSemestre,$nombre,$codigo,$abreviatura,$idEstado){
        try {
            $r = DB::select('CALL ACA_CON_Semestre(?,?,?,?,?)',[
                $idSemestre,$nombre,$codigo,$abreviatura,$idEstado
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idSemestre,$codigo,$nombre,$abreviatura,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL ACA_MNT_Semestre(?,?,?,?,?,?,?)',[
                $opcion,$idSemestre,$codigo,$nombre,$abreviatura,$idEstado,$codUsuario
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
