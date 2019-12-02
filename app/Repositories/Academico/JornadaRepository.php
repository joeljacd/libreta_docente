<?php
namespace App\Repositories\Academico;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class JornadaRepository{
    
    public function listar($idJornada,$codigo,$nombre,$abreviatura,$idEstado){
        try {
            $r = DB::select('CALL ACA_CON_Jornada(?,?,?,?,?)',[
                $idJornada,$codigo,$nombre,$abreviatura,$idEstado
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }


    public function insertarActualizar($opcion,$idJornada,$codigo,$nombre,$abreviatura,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL ACA_MNT_Jornada(?,?,?,?,?,?,?)',[
                $opcion,$idJornada,$codigo,$nombre,$abreviatura,$idEstado,$codUsuario
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
