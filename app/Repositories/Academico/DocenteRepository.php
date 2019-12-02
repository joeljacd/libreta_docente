<?php
namespace App\Repositories\Academico;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class DocenteRepository{
    
    public function listar($idDocente,$idPersona,$idEstado){
        try {
            $r = DB::select('CALL ACA_CON_Docente(?,?,?)',[
                $idDocente,$idPersona,$idEstado
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion,$idDocente,$idPersona,$titulo,$idEstado,$codUsuario){
        try {
            $r = DB::select('CALL ACA_MNT_Docente(?,?,?,?,?,?)',[
                    $opcion,$idDocente,$idPersona,$titulo,$idEstado,$codUsuario
                ]);

                return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }  
}
