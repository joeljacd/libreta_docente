<?php
namespace App\Repositories\Academico;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class CarreraRepository{
    
    public function listar($idCarrera,$nombre,$codigo,$abreviatura,$idEstado){
        try {
            $r = DB::select('CALL ACA_CON_Carrera(?,?,?,?,?)',[
                $idCarrera,$nombre,$codigo,$abreviatura,$idEstado
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idCarrera,$nombre,$codigo,$abreviatura,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL ACA_MNT_Carrera(?,?,?,?,?,?,?)',[
                $opcion,$idCarrera,$nombre,$codigo,$abreviatura,$idEstado,$codUsuario
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
